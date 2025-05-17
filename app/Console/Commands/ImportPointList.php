<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PointList;
use Illuminate\Support\Facades\Storage;

class ImportPointList extends Command
{
    protected $signature = 'import:pointlist';
    protected $description = 'Import point lists from a UTF-8 CSV file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = storage_path('app/point_lists.csv');

        if (!file_exists($path)) {
            $this->error("CSV file not found at: $path");
            return 1;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file); // 1行目をヘッダーとして取得

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);


            // 空文字列を null に変換
            $data = array_map(function ($value) {
                return $value === '' ? null : $value;
            }, $data);


            // モデルへ保存
            PointList::create($data);
            $count++;
        }

        fclose($file);

        $this->info("Imported $count records successfully.");
        return 0;//Command::SUCCESS;
    }

}
