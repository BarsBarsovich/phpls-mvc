<?php

namespace models\File;

use helpers\FileUploader;
use PDO;

class Files
{
    protected $table = 'files';
    protected $fillable = ['name', 'user_id'];

    public function __construct($user_id, $fileName)
    {
        $this->name = $fileName;
        $this->user_id = $user_id;
    }

    public static function upload(?int $userId = null, array $file = [])
    {
        $file = $file['upload'] ?? $file;
        if (empty($userId)) {
            $userId = $_SESSION['user_id'];
        }
        if (!empty($file) && FileUploader::upload($file)) {
            $file = new Files(['name' => $file['name'], 'userId' => $userId]);
            $file->save();
        } else {
            throw new FileException('Не удалось записать файл.');
        }
    }

    public function saveFileToDb()
    {
        $pdo = new PDO('mysql://localhost:3306/mvc', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $query = $pdo->prepare("insert into mvc.files (user_id, filename) values (:userId, :fileName)");
        $query->execute(['userId' => $this->user_id, 'fileName' => $this->name]);
    }

    public
    static function getAll(
        int $userId = 0,
        string $sort = 'desc'
    ) {
//        if(!empty($userId)){
//            $files = self::where('user_id', $userId)->orderBy('name', $sort)->get();
//        } else {
//            $files = self::orderBy('name', $sort)->get();
//        }
//        return $files;
//    }
    }
}