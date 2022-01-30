<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 30.01.2022
 * Time: 16:48
 */

namespace Core;

class Helper
{
    /**
     * Сохранить файл в медиа-хранилище
     *
     * @param string $data
     * @param string $fileName
     *
     * @return array
     */
    public static function saveToMedia(string $data, string $fileName): array
    {
        $relativePath = '/media/' . $fileName;
        $fullPath = PROJECT_ROOT_DIR . $relativePath;
        file_put_contents($fullPath, $data);

        return [
            'relative_path' => $relativePath,
            'full_path' => $fullPath
        ];
    }
}
