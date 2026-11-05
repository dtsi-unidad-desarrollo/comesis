<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup 
                            {--connection= : Conexión de base de datos a respaldar (por defecto la conexión activa)}
                            {--compress : Comprime el archivo de respaldo en formato .sql.gz para ahorrar espacio}
                            {--keep= : Número máximo de respaldos recientes a conservar (elimina los más antiguos)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera un respaldo (backup) SQL de la base de datos con opciones de compresión y retención';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $connectionName = $this->option('connection') ?: config('database.default');
        $databaseName = config("database.connections.{$connectionName}.database");

        $this->info("Iniciando respaldo de la base de datos: [{$databaseName}]...");

        try {
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $dateString = Carbon::now()->format('Y-m-d_H-i-s');
            $fileName = "backup_{$databaseName}_{$dateString}.sql";
            $fullPath = $backupPath . DIRECTORY_SEPARATOR . $fileName;

            $fileHandle = fopen($fullPath, 'w+');
            if (!$fileHandle) {
                throw new \Exception("No se pudo crear el archivo de respaldo en: {$fullPath}");
            }

            // Encabezado del archivo SQL
            fwrite($fileHandle, "-- ====================================================\n");
            fwrite($fileHandle, "-- BACKUP DE BASE DE DATOS: {$databaseName}\n");
            fwrite($fileHandle, "-- FECHA Y HORA: " . Carbon::now()->format('Y-m-d H:i:s') . "\n");
            fwrite($fileHandle, "-- SISTEMA: COMESIS\n");
            fwrite($fileHandle, "-- ====================================================\n\n");
            fwrite($fileHandle, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($fileHandle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($fileHandle, "SET time_zone = \"+00:00\";\n\n");

            $tables = DB::connection($connectionName)->select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
            $tableKey = "Tables_in_{$databaseName}";

            foreach ($tables as $tableObj) {
                $tableProp = (array) $tableObj;
                $tableName = reset($tableProp);

                $this->line("Respaldando tabla: {$tableName}...");

                // Estructura de la tabla
                $createTableResult = DB::connection($connectionName)->select("SHOW CREATE TABLE `{$tableName}`");
                $createTableProp = (array) $createTableResult[0];
                $createTableSql = $createTableProp['Create Table'];

                fwrite($fileHandle, "\n-- ----------------------------------------------------\n");
                fwrite($fileHandle, "-- Estructura de tabla para `{$tableName}`\n");
                fwrite($fileHandle, "-- ----------------------------------------------------\n");
                fwrite($fileHandle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                fwrite($fileHandle, $createTableSql . ";\n\n");

                // Datos de la tabla
                fwrite($fileHandle, "-- Volcado de datos para la tabla `{$tableName}`\n");
                $pdo = DB::connection($connectionName)->getPdo();

                DB::connection($connectionName)->table($tableName)->orderBy(
                    DB::connection($connectionName)->raw('1')
                )->chunk(500, function ($rows) use ($fileHandle, $tableName, $pdo) {
                    if ($rows->isEmpty()) {
                        return;
                    }

                    foreach ($rows as $row) {
                        $rowArray = (array) $row;
                        $columns = array_keys($rowArray);
                        $escapedColumns = array_map(function ($col) {
                            return "`" . str_replace("`", "``", $col) . "`";
                        }, $columns);

                        $values = [];
                        foreach ($rowArray as $val) {
                            if (is_null($val)) {
                                $values[] = 'NULL';
                            } elseif (is_numeric($val) && !is_string($val)) {
                                $values[] = $val;
                            } else {
                                $values[] = $pdo->quote((string) $val);
                            }
                        }

                        $insertSql = "INSERT INTO `{$tableName}` (" . implode(', ', $escapedColumns) . ") VALUES (" . implode(', ', $values) . ");\n";
                        fwrite($fileHandle, $insertSql);
                    }
                });
            }

            fwrite($fileHandle, "\nSET FOREIGN_KEY_CHECKS=1;\n");
            fwrite($fileHandle, "-- FIN DEL BACKUP\n");
            fclose($fileHandle);

            // Manejo de compresión si se solicita
            if ($this->option('compress')) {
                $gzPath = $fullPath . '.gz';
                $gzFileName = $fileName . '.gz';

                $this->line("Comprimiendo respaldo a formato GZIP...");
                $sourceContent = file_get_contents($fullPath);
                $compressedContent = gzencode($sourceContent, 9);

                if ($compressedContent !== false && file_put_contents($gzPath, $compressedContent) !== false) {
                    unlink($fullPath); // Elimina el archivo .sql sin comprimir
                    $fullPath = $gzPath;
                    $fileName = $gzFileName;
                } else {
                    $this->warn("No se pudo comprimir el archivo. Se mantendrá el respaldo en formato .sql plano.");
                }
            }

            $fileSize = round(filesize($fullPath) / 1024, 2);
            $successMsg = "Respaldo completado exitosamente: {$fileName} ({$fileSize} KB)";
            $this->info($successMsg);
            Log::info("Backup de base de datos generado: {$fullPath} ({$fileSize} KB)");

            // Política de retención si se especifica --keep
            $keep = $this->option('keep');
            if ($keep && is_numeric($keep) && (int) $keep > 0) {
                $this->applyRetentionPolicy($backupPath, $databaseName, (int) $keep);
            }

            return 0;
        } catch (\Throwable $e) {
            $errorMsg = "Error al generar el respaldo: " . $e->getMessage();
            $this->error($errorMsg);
            Log::error($errorMsg, ['exception' => $e]);
            return 1;
        }
    }

    /**
     * Aplica la política de retención eliminando los respaldos más antiguos.
     */
    protected function applyRetentionPolicy(string $backupPath, string $databaseName, int $keep): void
    {
        $pattern = $backupPath . DIRECTORY_SEPARATOR . "backup_{$databaseName}_*";
        $files = glob($pattern);

        if (!$files || count($files) <= $keep) {
            return;
        }

        // Ordenar archivos por fecha de modificación descendente (más nuevos primero)
        usort($files, function ($a, $b) {
            return filemtime($b) <=> filemtime($a);
        });

        // Eliminar los que excedan el límite $keep
        $filesToDelete = array_slice($files, $keep);
        $deletedCount = 0;

        foreach ($filesToDelete as $file) {
            if (File::exists($file) && unlink($file)) {
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            $this->info("Política de retención aplicada: Se conservaron los {$keep} respaldos más recientes y se eliminaron {$deletedCount} respaldos antiguos.");
        }
    }
}
