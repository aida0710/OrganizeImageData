<?php

namespace lazyperson0710\OrganizeImageData\io;

use Error;

class ImageFileManagement {

    private static ImageFileManagement $instance;
    public const ImageDirectory = '../../../Images';
    public array $imageDirectoryList = [];

    public function checkImagesFolder(): void {
        if (!is_dir(self::ImageDirectory)) {
            throw new Error("imageフォルダが存在しません");
        }
    }

    public function countImage(): int {
        $this->checkImagesFolder();
        $list = $this->setFileList(self::ImageDirectory);
        $this->imageDirectoryList = $list;
        $count = count($list);
        if ($count === 0) {
            throw new Error("imageフォルダに画像が存在しません");
        }
        return $count;
    }

    public function setFileList($dir, ?bool $getDate = false): array {
        $files = glob(rtrim($dir, '/') . '/*');
        $list = [];
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($getDate) {
                    if (exif_read_data($file) === false) {
                        var_dump("false判定くらったー！！！！");
                        $list[] = [
                            'file' => $file,
                            'date' => false,
                        ];
                    } elseif (isset(exif_read_data($file)['DateTimeOriginal'])) {
                        $exifDatePattern = '/\A(?<year>\d{4}):(?<month>\d{2}):(?<day>\d{2}) (?<hour>\d{2}):(?<minute>\d{2}):(?<second>\d{2})\z/';
                        if (preg_match($exifDatePattern, exif_read_data($file)['DateTimeOriginal'], $matches)) {
                            $dateTime = sprintf('%d%d%d%d%d%d',
                                $matches['year'],
                                $matches['month'],
                                $matches['day'],
                                $matches['hour'],
                                $matches['minute'],
                                $matches['second'],
                            );
                        } else throw new Error("日付の取得に失敗しました");
                        $list[] = [
                            'file' => $file,
                            'date' => $dateTime,
                        ];
                    } else {
                        $list[] = [
                            'file' => $file,
                            'date' => false,
                        ];
                    }
                } else {
                    $list[] = $file;
                }
            }
            if (is_dir($file)) {
                $list = array_merge($list, $this->setFileList($file));
            }
        }
        return $list;
    }

    public function moveFile(): int {
        $this->checkImagesFolder();
        $list = $this->imageDirectoryList;
        $count = 0;
        foreach ($list as $file) {
            if ($file === self::ImageDirectory . '/' . basename($file)) {
                continue;
            }
            if (is_file(self::ImageDirectory . '/' . basename($file))) {
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $rename = $fileName . '_' . mt_rand(100000000, 999999999);
                if (!rename($file, self::ImageDirectory . '/' . $rename . "." . $extension)) {
                    throw new Error("ファイルの名前変更に失敗しました");
                }
                $count++;
            } elseif (rename($file, self::ImageDirectory . '/' . basename($file))) {
                $count++;
            } else throw new Error("ファイルの移動に失敗しました");
        }
        return $count;
    }

    public function emptyDirectoryDelete(): void {
        $files = glob(rtrim(self::ImageDirectory . '/', '/') . '/*', GLOB_BRACE);
        foreach ($files as $file) {
            if (is_dir($file)) {
                var_dump($file);
                array_map('unlink', $files);
            }
        }
        $list = $this->setFileList(self::ImageDirectory);
        $this->imageDirectoryList = $list;
    }

    public static function getInstance(): ImageFileManagement {
        if (!isset(self::$instance)) {
            self::$instance = new ImageFileManagement();
        }
        return self::$instance;
    }
}