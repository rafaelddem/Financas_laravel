<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\ConfigurationRepository;
use Illuminate\Http\UploadedFile;

class ConfigurationService extends BaseService
{
    private string $db;
    private string $user;
    private string $pass;
    private string $host;
    private string $backupPassword;

    public function __construct()
    {
        $this->repository = app(ConfigurationRepository::class);

        $this->db = config('database.connections.mysql.database');
        $this->user = config('database.connections.mysql.username');
        $this->pass = config('database.connections.mysql.password');
        $this->host = config('database.connections.mysql.host');
        $this->backupPassword = env('BACKUP_COMPRESSION_PASSWORD');
    }

    public function updateConfigurations(array $input)
    {
        try {
            foreach ($input as $key => $value) {
                $this->repository->updateConfigurations($key, ['value' => $value]);
                config(['services.settings.' . strtolower($key) => $value]);
            }
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function backup()
    {
        try {
            $fileName = "backup_financas_" . now()->format('YmdHis') . ".zip";
            $backupFilePath = storage_path("app/backups/" . $fileName);

            $configPath = storage_path("app/backups/temp_config_backup.conf");
            file_put_contents($configPath, "[client]\nuser={$this->user}\npassword=\"{$this->pass}\"\nhost={$this->host}");

            $output = [];
            $resultCode = null;
            exec("mysqldump --defaults-extra-file={$configPath} {$this->db} | zip -P {$this->backupPassword} {$backupFilePath} - 2>&1", $output, $resultCode);

            if ($resultCode !== 0) {
                throw new BaseException();
                // Encontrar uma forma de salvar o $output e $resultCode sem enviar para o front
            }
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        } finally {
            if (file_exists($configPath)) unlink($configPath);
        }
    }

    public function backupDownload()
    {
        try {
            $fileName = "backup_financas_" . now()->format('YmdHis') . ".zip";
            $backupZipPath = storage_path("app/backups/" . $fileName);

            $configPath = storage_path("app/backups/temp_config_backup.conf");
            file_put_contents($configPath, "[client]\nuser={$this->user}\npassword=\"{$this->pass}\"\nhost={$this->host}");

            $output = [];
            $resultCode = null;
            exec("mysqldump --defaults-extra-file={$configPath} {$this->db} | zip -P {$this->backupPassword} {$backupZipPath} -", $output, $resultCode);

            if ($resultCode !== 0) {
                throw new BaseException();
                // Encontrar uma forma de salvar o $output e $resultCode sem enviar para o front
            }

            return response()->download($backupZipPath)->deleteFileAfterSend(true);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        } finally {
            if (file_exists($configPath)) unlink($configPath);
        }
    }

    public function restore(UploadedFile $file)
    {
        try {
            $backupFilePath = $file->getRealPath();
            $configPath = storage_path("app/backups/temp_config_restore.conf");
            file_put_contents($configPath, "[client]\nuser={$this->user}\npassword=\"{$this->pass}\"\nhost={$this->host}");

            $output = [];
            $resultCode = null;
            exec("unzip -P {$this->backupPassword} -p {$backupFilePath} | mysql --defaults-extra-file={$configPath} {$this->db} 2>&1", $output, $resultCode);

            if ($resultCode !== 0) {
                throw new BaseException();
                // Encontrar uma forma de salvar o $output e $resultCode sem enviar para o front
            }

        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        } finally {
            if (file_exists($configPath)) unlink($configPath);
        }
    }
}
