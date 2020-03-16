<?php
namespace helpers;


class FileUploader
{
    public static function upload($file = [], $uploadDir = '/uploads/')
    {
        $destPath = "../uploads/" .  $file["avatar"]["name"];
        return move_uploaded_file($file["avatar"]["tmp_name"], $destPath);
    }
}
