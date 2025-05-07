<?php
require_once __DIR__ . '/../models/document.php';
require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__ . '/../utils/service.php';

require_once __DIR__ . '/user_service.php';
require_once __DIR__ . '/asset_service.php';
require_once __DIR__ . '/deliverable_service.php';

class DocumentService extends Service {
    private static string $UPLOAD_PATH = __DIR__ . '/../uploads/';
    public static function documentModel($id, $name, $date_deposition, $data, $file_type, $id_user, $id_deliverable, $id_asset) {
        $date_deposition = DateTime::createFromFormat('Y-m-d H:i:s', $date_deposition);
        $document = new Document($name, $date_deposition, $data, $file_type, $id_user, $id_deliverable, $id_asset, $id);
        $user = UserService::getById($id_user);
        if ($id_deliverable) {
            $deliverable = DeliverableService::getById($id_deliverable);
            $document->setDeliverable($deliverable);
        }
        if ($id_asset) {
            $asset = AssetService::getById($id_asset);
            $document->setAsset($asset);
        }
        $document->setUser($user);
        return $document;
    }

    public static function getAll() : array {
        $table = Document::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table`;";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $documents = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $name, $date_deposition, $data, $file_type, $id_user, $id_deliverable, $id_asset) {
            return self::documentModel($id, $name, $date_deposition, $data, $file_type, $id_user, $id_deliverable, $id_asset);
        });
        return $documents;
    }

    public static function getByDeliverableId(int $id_deliverable) : array {
        $table = Document::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id_deliverable = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $id_deliverable
        ]);
        $documents = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $name, $date_deposition, $data, $file_type, $id_user, $id_deliverable, $id_asset) {
            return self::documentModel($id, $name, $date_deposition, $data, $file_type, $id_user, $id_deliverable, $id_asset);
        });
        return $documents;
    }

    public static function getByAssetId(int $id_asset) : array {
        $table = Document::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id_asset = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $id_asset
        ]);
        $documents = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $name, $date_deposition, $data, $file_type, $id_user, $id_deliverable, $id_asset) {
            return self::documentModel($id, $name, $date_deposition, $data, $file_type, $id_user, $id_deliverable, $id_asset);
        });
        return $documents;
    }

    public static function getById($id) : ?Document {
        $table = Document::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $id
        ]);
        $documents = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $name, $date_deposition, $data, $file_type, $id_user, $id_deliverable, $id_asset) {
            return self::documentModel($id, $name, $date_deposition, $data, $file_type, $id_user, $id_deliverable, $id_asset);
        });
        return empty($documents) ? null : $documents[0];
    }

    public static function create(Document $document) : Document {
        $table = Document::getTableName();
        $db = DBAManager::getInstance();
        $query = "INSERT INTO `$table` (name, date_deposition, data, file_type, id_user, id_deliverable, id_asset)
                    VALUES (?, ?, ?, ?, ?, ?, ?);";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $document->getName(),
            $document->getDateDeposition()->format('Y-m-d H:i:s'),
            $document->getData(),
            $document->getFileType(),
            $document->getIdUser(),
            $document->getIdDeliverable(),
            $document->getIdAsset(),
        ]);
        $document->setId($db->lastInsertId());
        return $document;
    }

    public static function delete(Document $document) : Document {
        $table = Document::getTableName();
        $db = DBAManager::getInstance();
        $query = "DELETE FROM `$table` WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $document->getId()
        ]);
        return $document;
    }

    public static function saveFile(array $file, string $extensionFolder = "") : bool {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        [
            'tmp_path' => $fileTmpPath,
            'uploadDir' => $uploadFileDir,
            'path' => $dest_path,
        ] = self::getFileSpec($file, $extensionFolder);

        if(!file_exists($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        // Déplacer le fichier vers sa destination finale
        return move_uploaded_file($fileTmpPath, $dest_path);
    }

    public static function getFileSpec(array $file, string $extensionFolder = "") {
        $fileNameCmps = explode(".", $file['name']);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $file['name']) . '.' . $fileExtension;
        $uploadFileDir = self::$UPLOAD_PATH . $extensionFolder;
        $dest_path = $uploadFileDir . $newFileName;

        return [
            'uploadDir' => $uploadFileDir,
            'path' => $dest_path,
            'tmp_path' =>$file['tmp_name'],
            'name' => $file['name'],
            'size' => $file['size'],
            'extension' => $fileExtension,
        ];
    }

    public static function extractFiles(array $files) : array {
        $_files = [];
        for($i = 0; $i < count($files['name']); $i++) {
            $_files[] = [
                'error' => $files['error'][$i],
                'full_path' => $files['full_path'][$i],
                'name' => $files['name'][$i],
                'size' => $files['size'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'type' => $files['type'][$i],
            ];
        }
        return $_files;
    }

    public static function deleteFile(Document $document) : bool {
        $fullpath = $document->getData();
        if (file_exists($fullpath)) {
            return unlink($fullpath);
        }
        return false;
    }

    public static function download(Document $document) : void {
        $fullpath = $document->getData();
        $name = $document->getName() . '.' . $document->getFileType();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'. $name .'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fullpath));
        // Vider le buffer de sortie
        flush();
        // Lire le fichier et l'envoyer au client
        readfile($fullpath);
    }

    public static function downloads(array $documents) : bool {
        if (empty($documents)) {
            return false;
        }
        $zip = new ZipArchive();
        $zipFilename = tempnam(sys_get_temp_dir(), 'documents_') . '.zip';
        if ($zip->open($zipFilename, ZipArchive::CREATE) !== true) {
            return false;
        }
        // Ajouter chaque document au ZIP
        foreach ($documents as $document) {
            $filePath = $document->getData();
            $fileName = $document->getName() . '.' . $document->getFileType();
            if (!file_exists($filePath)) {
                continue;
            }
            $zip->addFile($filePath, $fileName);
        }
        $zip->close();

        // Envoyer le ZIP en téléchargement
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="documents_' . date('Y-m-d') . '.zip"');
        header('Content-Length: ' . filesize($zipFilename));
        header('Pragma: no-cache');
        header('Expires: 0');
        readfile($zipFilename);
        unlink($zipFilename);
        return true;
    }
}