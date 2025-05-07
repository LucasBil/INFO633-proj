<?php

require_once __DIR__ . '/../services/document_service.php';
require_once __DIR__ . '/../utils/controller.php';
require_once __DIR__ . '/../models/enum/role.php';

class DocumentController extends Controller {
    public static function getAll() {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $documents = DocumentService::getAll();
        return self::sendResponse($documents);
    }

    public static function getById($id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $document = DocumentService::getById($id);
        if(!$document) {
            return self::sendError('Document not found', 404);
        }
        return self::sendResponse($document);
    }

    public static function getByDeliverableId($id_deliverable) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $document = DocumentService::getByDeliverableId($id_deliverable);
        return self::sendResponse($document);
    }

    public static function getByAssetId($id_asset) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $document = DocumentService::getByAssetId($id_asset);
        return self::sendResponse($document);
    }

    public static function create() {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $data = self::getRequestData();
        $name = $data['name'];
        $userId = TokenManager::getInstance()->getTokenData(self::getToken() ?? '')['id'];
        $now = new DateTime();

        $deliverable = isset($data['id_deliverable']) ? DeliverableService::getById($data['id_deliverable']) : null;
        $asset = isset($data['id_asset']) ? AssetService::getById($data['id_asset']) : null;
        $user = UserService::getById($userId);
        if (!$deliverable && !$asset) {
            return self::sendError('Ressource link not found', 404);
        }
        $document = new Document(
            $name,
            $now,
            null,
            null,
            $userId,
            $deliverable?->getId() ?? null,
            $asset?->getId() ?? null
        );
        $document->setDeliverable($deliverable);
        $document->setAsset($asset);
        $document->setUser($user);

        if (isset($data['file'])) {
            $extensionPath = $document->getIdDeliverable() ?
                'deliverable/' . $document->getIdDeliverable() . '/'  :
                'asset/' . $document->getIdAsset() . '/' ;
            [
                'path' => $fullPath,
                'extension' => $fileExtension,
            ] = DocumentService::getFileSpec($data['file'], $extensionPath);
            $document->setFileType($fileExtension);
            $document->setData($fullPath);
            DocumentService::saveFile($data['file'], $extensionPath);
        } else if (isset($data['url'])) {
            $document->setData($data['url']);
        } else {
            return self::sendError('Missing keys [file|url]');
        }

        $document = DocumentService::create($document);
        return self::sendResponse($document);
    }

    public static function delete(int $id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $document = DocumentService::getById($id);
        if (!$document) {
            return self::sendError('Document not found', 404);
        }
        if ($document->getFileType()) {
            DocumentService::deleteFile($document);
        }
        $document = DocumentService::delete($document);
        return self::sendResponse($document);
    }

    public static function download (int $id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $document = DocumentService::getById($id);
        if (!$document) {
            return self::sendError('Document not found', 404);
        }
        if ($document->getFileType()) {
            return DocumentService::download($document);
        }
        return self::sendResponse($document);
    }

    public static function downloadByAssetId (int $id_asset) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $documents = DocumentService::getByAssetId($id_asset);
        $documents = array_filter($documents, function($document){
            return $document->getFileType();
        });
        if (!empty($documents)) {
            return DocumentService::downloads($documents);
        }
        return self::sendResponse($documents);
    }

    public static function downloadByDeliverableId (int $id_deliverable) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $documents = DocumentService::getByDeliverableId($id_deliverable);
        $documents = array_filter($documents, function($document){
            return $document->getFileType();
        });
        if (!empty($documents)) {
            return DocumentService::downloads($documents);
        }
        return self::sendResponse($documents);
    }
}