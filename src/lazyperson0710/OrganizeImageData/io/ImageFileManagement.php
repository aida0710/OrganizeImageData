<?php

namespace lazyperson0710\OrganizeImageData\io;

use Error;

class ImageFileManagement {

    private static ImageFileManagement $instance;
    public const ImageDirectory = '../../../Images';
    public array $imageDirectoryList = [];

    public const Extensions = [
        'jpg',
        'jpeg',
        'png',
    ];

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

    public function setFileList($dir): array {
        $files = glob(rtrim($dir, '/') . '/*');
        $list = [];
        foreach ($files as $file) {
            if (is_file($file)) {
                $list[] = $file;
            }
            if (is_dir($file)) {
                $list = array_merge($list, $this->setFileList($file));
            }
        }
        return $list;
    }

    public function deleteOtherFile(): int {
        $this->checkImagesFolder();
        $list = $this->imageDirectoryList;
        $count = 0;
        foreach ($list as $key => $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array(mb_strtolower($extension), self::Extensions)) {
                unlink($file);
                unset($this->imageDirectoryList[$key]);
                $count++;
            }
        }
        return $count;
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