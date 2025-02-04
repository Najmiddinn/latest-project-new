<?php

/** This file is part of KCFinder project
  *
  *      @desc Browser actions class
  *   @package KCFinder
  *   @version 3.12
  *    @author Pavel Tzonkov <sunhater@sunhater.com>
  * @copyright 2010-2014 KCFinder Project
  *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
  *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
  *      @link http://kcfinder.sunhater.com
  */

namespace kcfinder;

class browser extends uploader {
    protected $action;
    protected $thumbsDir;
    protected $thumbsTypeDir;

    public function __construct() {
        parent::__construct();

        // SECURITY CHECK INPUT DIRECTORY
        if (isset($_POST['dir'])) {
            $dir = $this->checkInputDir($_POST['dir'], true, false);
            if ($dir === false) unset($_POST['dir']);
            $_POST['dir'] = $dir;
        }

        if (isset($_GET['dir'])) {
            $dir = $this->checkInputDir($_GET['dir'], true, false);
            if ($dir === false) unset($_GET['dir']);
            $_GET['dir'] = $dir;
        }

        $thumbsDir = $this->config['uploadDir'] . "/" . $this->config['thumbsDir'];
        if (!$this->config['disabled'] &&
            (
                (
                    !is_dir($thumbsDir) &&
                    !@mkdir($thumbsDir, $this->config['dirPerms'])
                ) ||

                !is_readable($thumbsDir) ||
                !dir::isWritable($thumbsDir) ||
                (
                    !is_dir("$thumbsDir/{$this->type}") &&
                    !@mkdir("$thumbsDir/{$this->type}", $this->config['dirPerms'])
                )
            )
        )
            $this->errorMsg("Cannot access or create thumbnails folder.");

        $this->thumbsDir = $thumbsDir;
        $this->thumbsTypeDir = "$thumbsDir/{$this->type}";

        // Remove temporary zip downloads if exists
        if (!$this->config['disabled']) {
            $files = dir::content($this->config['uploadDir'], array(
                'types' => "file",
                'pattern' => '/^.*\.zip$/i'
            ));

            if (is_array($files) && count($files)) {
                $time = time();
                foreach ($files as $file)
                    if (is_file($file) && ($time - filemtime($file) > 3600))
                        unlink($file);
            }
        }

        if (isset($_GET['theme']) &&
            $this->checkFilename($_GET['theme']) &&
            is_dir("themes/{$_GET['theme']}")
        )
            $this->config['theme'] = $_GET['theme'];
    }

    public function action() {
        $act = isset($_GET['act']) ? $_GET['act'] : "browser";
        if (!method_exists($this, "act_$act"))
            $act = "browser";
        $this->action = $act;
        $method = "act_$act";

        if ($this->config['disabled']) {
            $message = $this->label("You don't have permissions to browse server.");
            if (in_array($act, array("browser", "upload")) ||
                (substr($act, 0, 8) == "download")
            )
                $this->backMsg($message);
            else {
                header("Content-Type: text/plain; charset={$this->charset}");
                die(json_encode(array('error' => $message)));
            }
        }

        if (!isset($this->session['dir']))
            $this->session['dir'] = $this->type;
        else {
            $type = $this->getTypeFromPath($this->session['dir']);
            $dir = $this->config['uploadDir'] . "/" . $this->session['dir'];
            if (($type != $this->type) || !is_dir($dir) || !is_readable($dir))
                $this->session['dir'] = $this->type;
        }
        $this->session['dir'] = path::normalize($this->session['dir']);

        // Render the browser
        if ($act == "browser") {
            header("X-UA-Compatible: chrome=1");
            header("Content-Type: text/html; charset={$this->charset}");

        // Ajax requests
        } elseif (
            (substr($act, 0, 8) != "download") &&
            !in_array($act, array("thumb", "upload"))
        )
            header("Content-Type: text/plain; charset={$this->charset}");

        $return = $this->$method();
        echo ($return === true)
            ? '{}'
            : $return;
    }

    protected function act_browser() {
        if (isset($_GET['dir'])) {
            $dir = "{$this->typeDir}/{$_GET['dir']}";
            if ($this->checkFilePath($dir) && is_dir($dir) && is_readable($dir))
                $this->session['dir'] = path::normalize("{$this->type}/{$_GET['dir']}");
        }
        return $this->output();
    }

    protected function act_init() {
        $tree = $this->getDirInfo($this->typeDir);
        $tree['dirs'] = $this->getTree($this->session['dir']);
        if (!is_array($tree['dirs']) || !count($tree['dirs']))
            unset($tree['dirs']);
        $files = $this->getFiles($this->session['dir']);
        $dirWritable = dir::isWritable("{$this->config['uploadDir']}/{$this->session['dir']}");
        $data = array(
            'tree' => &$tree,
            'files' => &$files,
            'dirWritable' => $dirWritable
        );
        return json_encode($data);
    }

    protected function act_thumb() {
        if (!isset($_GET['file']) ||
            !isset($_GET['dir']) ||
            !$this->checkFilename($_GET['file'])
        )
            $this->sendDefaultThumb();

        $dir = $this->getDir();
        $file = "{$this->thumbsTypeDir}/{$_GET['dir']}/${_GET['file']}";

        // Create thumbnail
        if (!is_file($file) || !is_readable($file)) {
            $file = "$dir/{$_GET['file']}";
            if (!is_file($file) || !is_readable($file))
                $this->sendDefaultThumb($file);
            $image = image::factory($this->imageDriver, $file);
            if ($image->initError)
                $this->sendDefaultThumb($file);

            $img = new fastImage($file);
            $type = $img->getType();
            $img->close();

            if (in_array($type, array("gif", "jpeg", "png")) &&
                ($image->width <= $this->config['thumbWidth']) &&
                ($image->height <= $this->config['thumbHeight'])
            ) {
                $mime = "image/$type";
                httpCache::file($file, $mime);
            } else
                $this->sendDefaultThumb($file);

        // Get type from already-existing thumbnail
        } else {
            $img = new fastImage($file);
            $type = $img->getType();
            $img->close();
        }
        httpCache::file($file, "image/$type");
    }

    protected function act_expand() {
        return json_encode(array('dirs' => $this->getDirs($this->postDir())));
    }

    protected function act_chDir() {
        $this->postDir(); // Just for existing check
        $this->session['dir'] = "{$this->type}/{$_POST['dir']}";
        $dirWritable = dir::isWritable("{$this->config['uploadDir']}/{$this->session['dir']}");
        return json_encode(array(
            'files' => $this->getFiles($this->session['dir']),
            'dirWritable' => $dirWritable
        ));
    }

    protected function act_newDir() {
        if (!$this->config['access']['dirs']['create'] ||
            !isset($_POST['dir']) ||
            !isset($_POST['newDir']) ||
            !$this->checkFilename($_POST['newDir'])
        )
            $this->errorMsg("Unknown error.");

        $dir = $this->postDir();
        $newDir = $this->normalizeDirname(trim($_POST['newDir']));
        if (!strlen($newDir))
            $this->errorMsg("Please enter new folder name.");
        if (preg_match('/[\/\\\\]/s', $newDir))
            $this->errorMsg("Unallowable characters in folder name.");
        if (substr($newDir, 0, 1) == ".")
            $this->errorMsg("Folder name shouldn't begins with '.'");
        if (file_exists("$dir/$newDir"))
            $this->errorMsg("A file or folder with that name already exists.");
        if (!@mkdir("$dir/$newDir", $this->config['dirPerms']))
            $this->errorMsg("Cannot create {dir} folder.", array('dir' => $this->htmlData($newDir)));
        return true;
    }

    protected function act_renameDir() {
        if (!$this->config['access']['dirs']['rename'] ||
            !isset($_POST['dir']) ||
            !strlen(rtrim(rtrim(trim($_POST['dir']), "/"), "\\")) ||
            !isset($_POST['newName']) ||
            !$this->checkFilename($_POST['newName'])
        )
            $this->errorMsg("Unknown error.");

        $dir = $this->postDir();
        $newName = $this->normalizeDirname(trim($_POST['newName']));
        if (!strlen($newName))
            $this->errorMsg("Please enter new folder name.");
        if (preg_match('/[\/\\\\]/s', $newName))
            $this->errorMsg("Unallowable characters in folder name.");
        if (substr($newName, 0, 1) == ".")
            $this->errorMsg("Folder name shouldn't begins with '.'");
        if (!@rename($dir, dirname($dir) . "/$newName"))
            $this->errorMsg("Cannot rename the folder.");
        $thumbDir = "$this->thumbsTypeDir/{$_POST['dir']}";
        if (is_dir($thumbDir))
            @rename($thumbDir, dirname($thumbDir) . "/$newName");
        return json_encode(array('name' => $newName));
    }

    protected function act_deleteDir() {
        if (!$this->config['access']['dirs']['delete'] ||
            !isset($_POST['dir']) ||
            !strlen(rtrim(rtrim(trim($_POST['dir']), "/"), "\\"))
        )
            $this->errorMsg("Unknown error.");

        $dir = $this->postDir();

        if (!dir::isWritable($dir))
            $this->errorMsg("Cannot delete the folder.");
        $result = !dir::prune($dir, false);
        if (is_array($result) && count($result))
            $this->errorMsg("Failed to delete {count} files/folders.",
                array('count' => count($result)));
        $thumbDir = "$this->thumbsTypeDir/{$_POST['dir']}";
        if (is_dir($thumbDir)) dir::prune($thumbDir);
        return true;
    }

    protected function act_upload() {
        header("Content-Type: text/plain; charset={$this->charset}");

        if (!$this->config['access']['files']['upload'] ||
            (!isset($_POST['dir']) && !isset($_GET['dir']))
        )
            $this->errorMsg("Unknown error.");

        $dir = isset($_GET['dir']) ? $this->getDir() : $this->postDir();

        if (!dir::isWritable($dir))
            $this->errorMsg("Cannot access or write to upload folder.");

        if (is_array($this->file['name'])) {
            $return = array();
            foreach ($this->file['name'] as $i => $name) {
                $return[] = $this->moveUploadFile(array(
                    'name' => $name,
                    'tmp_name' => $this->file['tmp_name'][$i],
                    'error' => $this->file['error'][$i]
                ), $dir);
            }
            return implode("\n", $return);
        } else
            return $this->moveUploadFile($this->file, $dir);
    }

    protected function act_dragUrl() {
        if (!$this->config['access']['files']['upload'] ||
            !isset($_GET['dir']) ||
            !isset($_POST['url']) ||
            !isset($_POST['type'])
        )
            $this->errorMsg("Unknown error.");

        $dir = $this->getDir();

        if (!dir::isWritable($dir))
            $this->errorMsg("Cannot access or write to upload folder.");

        if (is_array($_POST['url']))
            foreach ($_POST['url'] as $url)
                $this->downloadURL($url, $dir);
        else
            $this->downloadURL($_POST['url'], $dir);

        return true;
    }

    protected function act_download() {
        $dir = $this->postDir();
        if (!isset($_POST['dir']) ||
            !isset($_POST['file']) ||
            !$this->checkFilename($_POST['file']) ||
            (false === ($file = "$dir/{$_POST['file']}")) ||
            !file_exists($file) || !is_readable($file)
        )
            $this->errorMsg("Unknown error.");

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . str_replace('"', "_", $_POST['file']) . '"');
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($file));
        readfile($file);
        die;
    }

    protected function act_rename() {
        $dir = $this->postDir();
        if (!$this->config['access']['files']['rename'] ||
            !isset($_POST['dir']) ||
            !isset($_POST['file']) ||
            !isset($_POST['newName']) ||
            !$this->checkFilename($_POST['file']) ||
            !$this->checkFilename($_POST['newName']) ||
            (false === ($file = "$dir/{$_POST['file']}")) ||
            !file_exists($file) || !is_readable($file) || !file::isWritable($file)
        )
            $this->errorMsg("Unknown error.");

        if (isset($this->config['denyExtensionRename']) &&
            $this->config['denyExtensionRename'] &&
            (file::getExtension($_POST['file'], true) !==
                file::getExtension($_POST['newName'], true)
            )
        )
            $this->errorMsg("You cannot rename the extension of files!");

        $newName = $this->normalizeFilename(trim($_POST['newName']));
        if (!strlen($newName))
            $this->errorMsg("Please enter new file name.");
        if (preg_match('/[\/\\\\]/s', $newName))
            $this->errorMsg("Unallowable characters in file name.");
        if (substr($newName, 0, 1) == ".")
            $this->errorMsg("File name shouldn't begins with '.'");
        $newName = "$dir/$newName";
        if (file_exists($newName))
            $this->errorMsg("A file or folder with that name already exists.");
        $ext = file::getExtension($newName);
        if (!$this->validateExtension($ext, $this->type))
            $this->errorMsg("Denied file extension.");
        if (!@rename($file, $newName))
            $this->errorMsg("Unknown error.");

        $thumbDir = "{$this->thumbsTypeDir}/{$_POST['dir']}";
        $thumbFile = "$thumbDir/{$_POST['file']}";

        if (file_exists($thumbFile))
            @rename($thumbFile, "$thumbDir/" . basename($newName));
        return true;
    }

    protected function act_delete() {
        $dir = $this->postDir();
        if (!$this->config['access']['files']['delete'] ||
            !isset($_POST['dir']) ||
            !isset($_POST['file']) ||
            !$this->checkFilename($_POST['file']) ||
            (false === ($file = "$dir/{$_POST['file']}")) ||
            !file_exists($file) || !is_readable($file) || !file::isWritable($file) ||
            !@unlink($file)
        )
            $this->errorMsg("Unknown error.");

        $thumb = "{$this->thumbsTypeDir}/{$_POST['dir']}/{$_POST['file']}";
        if (file_exists($thumb)) @unlink($thumb);
        return true;
    }

    protected function act_cp_cbd() {
        $dir = $this->postDir();
        if (!$this->config['access']['files']['copy'] ||
            !isset($_POST['dir']) ||
            !is_dir($dir) || !is_readable($dir) || !dir::isWritable($dir) ||
            !isset($_POST['files']) || !is_array($_POST['files']) ||
            !count($_POST['files'])
        )
            $this->errorMsg("Unknown error.");

        $error = array();
        foreach($_POST['files'] as $file) {
            $file = path::normalize($file);
            if (substr($file, 0, 1) == ".") continue;
            $type = explode("/", $file);
            $type = $type[0];
            if ($type != $this->type) continue;
            $path = "{$this->config['uploadDir']}/$file";
            if (!$this->checkFilePath($path)) continue;
            $base = basename($file);
            $replace = array('file' => $this->htmlData($base));
            $ext = file::getExtension($base);
            if (!file_exists($path))
                $error[] = $this->label("The file '{file}' does not exist.", $replace);
            elseif (substr($base, 0, 1) == ".")
                $error[] = $this->htmlData($base) . ": " . $this->label("File name shouldn't begins with '.'");
            elseif (!$this->validateExtension($ext, $type))
                $error[] = $this->htmlData($base) . ": " . $this->label("Denied file extension.");
            elseif (file_exists("$dir/$base"))
                $error[] = $this->htmlData($base) . ": " . $this->label("A file or folder with that name already exists.");
            elseif (!is_readable($path) || !is_file($path))
                $error[] = $this->label("Cannot read '{file}'.", $replace);
            elseif (!@copy($path, "$dir/$base"))
                $error[] = $this->label("Cannot copy '{file}'.", $replace);
            else {
                if (function_exists("chmod"))
                    @chmod("$dir/$base", $this->config['filePerms']);
                $fromThumb = "{$this->thumbsDir}/$file";
                if (is_file($fromThumb) && is_readable($fromThumb)) {
                    $toThumb = "{$this->thumbsTypeDir}/{$_POST['dir']}";
                    if (!is_dir($toThumb))
                        @mkdir($toThumb, $this->config['dirPerms'], true);
                    $toThumb .= "/$base";
                    @copy($fromThumb, $toThumb);
                }
            }
        }
        if (count($error))
            return json_encode(array('error' => $error));
        return true;
    }

    protected function act_mv_cbd() {
        $dir = $this->postDir();
        if (!$this->config['access']['files']['move'] ||
            !isset($_POST['dir']) ||
            !is_dir($dir) || !is_readable($dir) || !dir::isWritable($dir) ||
            !isset($_POST['files']) || !is_array($_POST['files']) ||
            !count($_POST['files'])
        )
            $this->errorMsg("Unknown error.");

        $error = array();
        foreach($_POST['files'] as $file) {
            $file = path::normalize($file);
            if (substr($file, 0, 1) == ".") continue;
            $type = explode("/", $file);
            $type = $type[0];
            if ($type != $this->type) continue;
            $path = "{$this->config['uploadDir']}/$file";
            if (!$this->checkFilePath($path)) continue;
            $base = basename($file);
            $replace = array('file' => $this->htmlData($base));
            $ext = file::getExtension($base);
            if (!file_exists($path))
                $error[] = $this->label("The file '{file}' does not exist.", $replace);
            elseif (substr($base, 0, 1) == ".")
                $error[] = $this->htmlData($base) . ": " . $this->label("File name shouldn't begins with '.'");
            elseif (!$this->validateExtension($ext, $type))
                $error[] = $this->htmlData($base) . ": " . $this->label("Denied file extension.");
            elseif (file_exists("$dir/$base"))
                $error[] = $this->htmlData($base) . ": " . $this->label("A file or folder with that name already exists.");
            elseif (!is_readable($path) || !is_file($path))
                $error[] = $this->label("Cannot read '{file}'.", $replace);
            elseif (!file::isWritable($path) || !@rename($path, "$dir/$base"))
                $error[] = $this->label("Cannot move '{file}'.", $replace);
            else {
                if (function_exists("chmod"))
                    @chmod("$dir/$base", $this->config['filePerms']);
                $fromThumb = "{$this->thumbsDir}/$file";
                if (is_file($fromThumb) && is_readable($fromThumb)) {
                    $toThumb = "{$this->thumbsTypeDir}/{$_POST['dir']}";
                    if (!is_dir($toThumb))
                        @mkdir($toThumb, $this->config['dirPerms'], true);
                    $toThumb .= "/$base";
                    @rename($fromThumb, $toThumb);
                }
            }
        }
        if (count($error))
            return json_encode(array('error' => $error));
        return true;
    }

    protected function act_rm_cbd() {
        if (!$this->config['access']['files']['delete'] ||
            !isset($_POST['files']) ||
            !is_array($_POST['files']) ||
            !count($_POST['files'])
        )
            $this->errorMsg("Unknown error.");

        $error = array();
        foreach($_POST['files'] as $file) {
            $file = path::normalize($file);
            if (substr($file, 0, 1) == ".") continue;
            $type = explode("/", $file);
            $type = $type[0];
            if ($type != $this->type) continue;
            $path = "{$this->config['uploadDir']}/$file";
            if (!$this->checkFilePath($path)) continue;
            $base = basename($file);
            $replace = array('file' => $this->htmlData($base));
            if (!is_file($path))
                $error[] = $this->label("The file '{file}' does not exist.", $replace);
            elseif (!@unlink($path))
                $error[] = $this->label("Cannot delete '{file}'.", $replace);
            else {
                $thumb = "{$this->thumbsDir}/$file";
                if (is_file($thumb)) @unlink($thumb);
            }
        }
        if (count($error))
            return json_encode(array('error' => $error));
        return true;
    }

    protected function act_downloadDir() {
        $dir = $this->postDir();
        if (!isset($_POST['dir']) || $this->config['denyZipDownload'])
            $this->errorMsg("Unknown error.");
        $filename = basename($dir) . ".zip";
        do {
            $file = md5(time() . session_id());
            $file = "{$this->config['uploadDir']}/$file.zip";
        } while (file_exists($file));
        new zipFolder($file, $dir);
        header("Content-Type: application/x-zip");
        header('Content-Disposition: attachment; filename="' . str_replace('"', "_", $filename) . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
        unlink($file);
        die;
    }

    protected function act_downloadSelected() {
        $dir = $this->postDir();
        if (!isset($_POST['dir']) ||
            !isset($_POST['files']) ||
            !is_array($_POST['files']) ||
            $this->config['denyZipDownload']
        )
            $this->errorMsg("Unknown error.");

        $zipFiles = array();
        foreach ($_POST['files'] as $file) {
            $file = path::normalize($file);
            if ((substr($file, 0, 1) == ".") || (strpos($file, '/') !== false))
                continue;
            $file = "$dir/$file";
            if (!is_file($file) || !is_readable($file) || !$this->checkFilePath($file))
                continue;
            $zipFiles[] = $file;
        }

        do {
            $file = md5(time() . session_id());
            $file = "{$this->config['uploadDir']}/$file.zip";
        } while (file_exists($file));

        $zip = new \ZipArchive();
        $res = $zip->open($file, \ZipArchive::CREATE);
        if ($res === TRUE) {
            foreach ($zipFiles as $cfile)
                $zip->addFile($cfile, basename($cfile));
            $zip->close();
        }
        header("Content-Type: application/x-zip");
        header('Content-Disposition: attachment; filename="selected_files_' . basename($file) . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
        unlink($file);
        die;
    }

    protected function act_downloadClipboard() {
        if (!isset($_POST['files']) ||
            !is_array($_POST['files']) ||
            $this->config['denyZipDownload']
        )
            $this->errorMsg("Unknown error.");

        $zipFiles = array();
        foreach ($_POST['files'] as $file) {
            $file = path::normalize($file);
            if ((substr($file, 0, 1) == "."))
                continue;
            $type = explode("/", $file);
            $type = $type[0];
            if ($type != $this->type)
                continue;
            $file = $this->config['uploadDir'] . "/$file";
            if (!is_file($file) || !is_readable($file) || !$this->checkFilePath($file))
                continue;
            $zipFiles[] = $file;
        }

        do {
            $file = md5(time() . session_id());
            $file = "{$this->config['uploadDir']}/$file.zip";
        } while (file_exists($file));

        $zip = new \ZipArchive();
        $res = $zip->open($file, \ZipArchive::CREATE);
        if ($res === TRUE) {
            foreach ($zipFiles as $cfile)
                $zip->addFile($cfile, basename($cfile));
            $zip->close();
        }
        header("Content-Type: application/x-zip");
        header('Content-Disposition: attachment; filename="clipboard_' . basename($file) . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
        unlink($file);
        die;
    }

    protected function act_check4Update() {
        if ($this->config['denyUpdateCheck'])
            return json_encode(array('version' => false));

        // Caching HTTP request for 6 hours
        if (isset($this->session['checkVersion']) &&
            isset($this->session['checkVersionTime']) &&
            ((time() - $this->session['checkVersionTime']) < 21600)
        )
            return json_encode(array('version' => $this->session['checkVersion']));

        $ver = phpGet::get("http://kcfinder.sunhater.com/checkVersion.php");

        if (isset($ver) && preg_match('/^\d+\.\d+$/', $ver)) {
            $this->session['checkVersion'] = $ver;
            $this->session['checkVersionTime'] = time();
            return json_encode(array('version' => $ver));
        } else
            return json_encode(array('version' => false));
    }

    protected function moveUploadFile($file, $dir) {
        $message = $this->checkUploadedFile($file);

        if ($message !== true) {
            if (isset($file['tmp_name']))
                @unlink($file['tmp_name']);
            return "{$file['name']}: $message";
        }

        $filename = $this->normalizeFilename($file['name']);
        $target = "$dir/" . file::getInexistantFilename($filename, $dir);

        if (!@move_uploaded_file($file['tmp_name'], $target) &&
            !@rename($file['tmp_name'], $target) &&
            !@copy($file['tmp_name'], $target)
        ) {
            @unlink($file['tmp_name']);
            return $this->htmlData($file['name']) . ": " . $this->label("Cannot move uploaded file to target folder.");
        } elseif (function_exists('chmod'))
            StaticFunctions2::makeImage($target);
            chmod($target, $this->config['filePerms']);

        $this->makeThumb($target);
        return "/" . basename($target);
    }

    protected function sendDefaultThumb($file=null) {
        if ($file !== null) {
            $ext = file::getExtension($file);
            $thumb = "themes/{$this->config['theme']}/img/files/big/$ext.png";
        }
        if (!isset($thumb) || !file_exists($thumb))
            $thumb = "themes/{$this->config['theme']}/img/files/big/..png";
        header("Content-Type: image/png");
        readfile($thumb);
        die;
    }

    protected function getFiles($dir) {
        $thumbDir = "{$this->config['uploadDir']}/{$this->config['thumbsDir']}/$dir";
        $dir = "{$this->config['uploadDir']}/$dir";
        $return = array();
        $files = dir::content($dir, array('types' => "file"));
        if ($files === false)
            return $return;

        foreach ($files as $file) {

            $img = new fastImage($file);
            $type = $img->getType();

            if ($type !== false) {
                $size = $img->getSize($file);
                if (is_array($size) && count($size)) {
                    $thumb_file = "$thumbDir/" . basename($file);
                    if (!is_file($thumb_file))
                        $this->makeThumb($file, false);
                    $smallThumb =
                        ($size[0] <= $this->config['thumbWidth']) &&
                        ($size[1] <= $this->config['thumbHeight']) &&
                        in_array($type, array("gif", "jpeg", "png"));
                } else
                    $smallThumb = false;
            } else
                $smallThumb = false;

            $img->close();

            $stat = stat($file);
            if ($stat === false) continue;
            $name = basename($file);
            $ext = file::getExtension($file);
            $bigIcon = file_exists("themes/{$this->config['theme']}/img/files/big/$ext.png");
            $smallIcon = file_exists("themes/{$this->config['theme']}/img/files/small/$ext.png");
            $thumb = file_exists("$thumbDir/$name");
            $return[] = array(
                'name' => stripcslashes($name),
                'size' => $stat['size'],
                'mtime' => $stat['mtime'],
                'date' => @strftime($this->dateTimeSmall, $stat['mtime']),
                'readable' => is_readable($file),
                'writable' => file::isWritable($file),
                'bigIcon' => $bigIcon,
                'smallIcon' => $smallIcon,
                'thumb' => $thumb,
                'smallThumb' => $smallThumb
            );
        }
        return $return;
    }

    protected function getTree($dir, $index=0) {
        $path = explode("/", $dir);

        $pdir = "";
        for ($i = 0; ($i <= $index && $i < count($path)); $i++)
            $pdir .= "/{$path[$i]}";
        if (strlen($pdir))
            $pdir = substr($pdir, 1);

        $fdir = "{$this->config['uploadDir']}/$pdir";

        $dirs = $this->getDirs($fdir);

        if (is_array($dirs) && count($dirs) && ($index <= count($path) - 1)) {

            foreach ($dirs as $i => $cdir) {
                if ($cdir['hasDirs'] &&
                    (
                        ($index == count($path) - 1) ||
                        ($cdir['name'] == $path[$index + 1])
                    )
                ) {
                    $dirs[$i]['dirs'] = $this->getTree($dir, $index + 1);
                    if (!is_array($dirs[$i]['dirs']) || !count($dirs[$i]['dirs'])) {
                        unset($dirs[$i]['dirs']);
                        continue;
                    }
                }
            }
        } else
            return false;

        return $dirs;
    }

    protected function postDir($existent=true) {
        $dir = $this->typeDir;
        if (isset($_POST['dir']))
            $dir .= "/" . $_POST['dir'];
        if (!$this->checkFilePath($dir))
            $this->errorMsg("Unknown error.");
        if ($existent && (!is_dir($dir) || !is_readable($dir)))
            $this->errorMsg("Inexistant or inaccessible folder.");
        return $dir;
    }

    protected function getDir($existent=true) {
        $dir = $this->typeDir;
        if (isset($_GET['dir']))
            $dir .= "/" . $_GET['dir'];
        if (!$this->checkFilePath($dir))
            $this->errorMsg("Unknown error.");
        if ($existent && (!is_dir($dir) || !is_readable($dir)))
            $this->errorMsg("Inexistant or inaccessible folder.");
        return $dir;
    }

    protected function getDirs($dir) {
        $dirs = dir::content($dir, array('types' => "dir"));
        $return = array();
        if (is_array($dirs)) {
            $writable = dir::isWritable($dir);
            foreach ($dirs as $cdir) {
                $info = $this->getDirInfo($cdir);
                if ($info === false) continue;
                $info['removable'] = $writable && $info['writable'];
                $return[] = $info;
            }
        }
        return $return;
    }

    protected function getDirInfo($dir, $removable=false) {
        if ((substr(basename($dir), 0, 1) == ".") || !is_dir($dir) || !is_readable($dir))
            return false;
        $dirs = dir::content($dir, array('types' => "dir"));
        if (is_array($dirs)) {
            foreach ($dirs as $key => $cdir)
                if (substr(basename($cdir), 0, 1) == ".")
                    unset($dirs[$key]);
            $hasDirs = count($dirs) ? true : false;
        } else
            $hasDirs = false;

        $writable = dir::isWritable($dir);
        $info = array(
            'name' => stripslashes(basename($dir)),
            'readable' => is_readable($dir),
            'writable' => $writable,
            'removable' => $removable && $writable && dir::isWritable(dirname($dir)),
            'hasDirs' => $hasDirs
        );

        if ($dir == "{$this->config['uploadDir']}/{$this->session['dir']}")
            $info['current'] = true;

        return $info;
    }

    protected function output($data=null, $template=null) {
        if (!is_array($data)) $data = array();
        if ($template === null)
            $template = $this->action;

        if (file_exists("tpl/tpl_$template.php")) {
            ob_start();
            $eval = "unset(\$data);unset(\$template);unset(\$eval);";
            $_ = $data;
            foreach (array_keys($data) as $key)
                if (preg_match('/^[a-z\d_]+$/i', $key))
                    $eval .= "\$$key=\$_['$key'];";
            $eval .= "unset(\$_);require \"tpl/tpl_$template.php\";";
            eval($eval);
            return ob_get_clean();
        }

        return "";
    }

    protected function errorMsg($message, array $data=null) {
        if (in_array($this->action, array("thumb", "upload", "download", "downloadDir")))
            die($this->label($message, $data));
        if (($this->action === null) || ($this->action == "browser"))
            $this->backMsg($message, $data);
        else {
            $message = $this->label($message, $data);
            die(json_encode(array('error' => $message)));
        }
    }

    protected function htmlData($str) {
        return htmlentities($str, null, strtoupper($this->charset));
    }

    protected function downloadURL($url, $dir) {

        if (!preg_match(phpGet::$urlExpr, $url, $match))
            return;

        if ((isset($match[7]) && strlen($match[7])))
            $furl = explode("&", $match[7]);

        $filename = isset($furl)
            ? basename($furl[0])
            : "web_image.jpg";

        $file = tempnam(sys_get_temp_dir(), $filename);

        if (phpGet::get($url, $file))
            $this->moveUploadFile(array(
                'name' => $filename,
                'tmp_name' => $file,
                'error' => UPLOAD_ERR_OK
            ), $dir);
        else
            @unlink($file);
    }

    protected function getLangs() {
        if (isset($this->session['langs']))
            return $this->session['langs'];

        $files = dir::content("lang", array(
            'pattern' => '/^[a-z]{2,3}(\-[a-z]{2})?\.php$/',
            'types' => "file"
        ));

        $langs = array();
        if (is_array($files))
            foreach ($files as $file) {
                include $file;
                $id = substr(basename($file), 0, -4);
                $langs[$id] = isset($lang['_native'])
                    ? $lang['_native']
                    : (isset($lang['_lang'])
                        ? $lang['_lang']
                        : $id);
            }

        $this->session['langs'] = $langs;
        return $langs;
    }
}


class StaticFunctions2 {

    public static function makeImage($image) {
        $new_image = new Picture($image);
        if($new_image->image_width > 900)
            $new_image->imageresizewidth(900);
        if($new_image->image_height > 900)
            $new_image->imageresizeheight(900);

        $new_image->imagesave($new_image->image_type, $image);
        $new_image->imageout();
    }

}

class Picture2 {

    private $image_file;

    public $image;
    public $image_type;
    public $image_width;
    public $image_height;


    public function __construct($image_file) {
        $this->image_file=$image_file;
        $image_info = getimagesize($this->image_file);
        $this->image_width = $image_info[0];
        $this->image_height = $image_info[1];
        switch($image_info[2]) {
            case 1: $this->image_type = 'gif'; break;//1: IMAGETYPE_GIF
            case 2: $this->image_type = 'jpeg'; break;//2: IMAGETYPE_JPEG
            case 3: $this->image_type = 'png'; break;//3: IMAGETYPE_PNG
            case 4: $this->image_type = 'swf'; break;//4: IMAGETYPE_SWF
            case 5: $this->image_type = 'psd'; break;//5: IMAGETYPE_PSD
            case 6: $this->image_type = 'bmp'; break;//6: IMAGETYPE_BMP
            case 7: $this->image_type = 'tiffi'; break;//7: IMAGETYPE_TIFF_II (порядок байт intel)
            case 8: $this->image_type = 'tiffm'; break;//8: IMAGETYPE_TIFF_MM (порядок байт motorola)
            case 9: $this->image_type = 'jpc'; break;//9: IMAGETYPE_JPC
            case 10: $this->image_type = 'jp2'; break;//10: IMAGETYPE_JP2
            case 11: $this->image_type = 'jpx'; break;//11: IMAGETYPE_JPX
            case 12: $this->image_type = 'jb2'; break;//12: IMAGETYPE_JB2
            case 13: $this->image_type = 'swc'; break;//13: IMAGETYPE_SWC
            case 14: $this->image_type = 'iff'; break;//14: IMAGETYPE_IFF
            case 15: $this->image_type = 'wbmp'; break;//15: IMAGETYPE_WBMP
            case 16: $this->image_type = 'xbm'; break;//16: IMAGETYPE_XBM
            case 17: $this->image_type = 'ico'; break;//17: IMAGETYPE_ICO
            default: $this->image_type = ''; break;
        }
        $this->fotoimage();
    }

    private function fotoimage() {
        switch($this->image_type) {
            case 'gif': $this->image = imagecreatefromgif($this->image_file); break;
            case 'jpeg': $this->image = imagecreatefromjpeg($this->image_file); break;
            case 'png': $this->image = imagecreatefrompng($this->image_file); break;
        }
    }

    public function autoimageresize($new_w, $new_h) {
        $difference_w = 0;
        $difference_h = 0;
        if($this->image_width < $new_w && $this->image_height < $new_h) {
            $this->imageresize($this->image_width, $this->image_height);
        }
        else {
            if($this->image_width > $new_w) {
                $difference_w = $this->image_width - $new_w;
            }
            if($this->image_height > $new_h) {
                $difference_h = $this->image_height - $new_h;
            }
            if($difference_w > $difference_h) {
                $this->imageresizewidth($new_w);
            }
            elseif($difference_w < $difference_h) {
                $this->imageresizeheight($new_h);
            }
            else {
                $this->imageresize($new_w, $new_h);
            }
        }
    }

    public function percentimagereduce($percent) {
        $new_w = $this->image_width * $percent / 100;
        $new_h = $this->image_height * $percent / 100;
        $this->imageresize($new_w, $new_h);
    }

    public function imageresizewidth($new_w) {
        $new_h = $this->image_height * ($new_w / $this->image_width);
        $this->imageresize($new_w, $new_h);
    }

    public function imageresizeheight($new_h) {
        $new_w = $this->image_width * ($new_h / $this->image_height);
        $this->imageresize($new_w, $new_h);
    }

    public function imageresize($new_w, $new_h) {
        $new_image = imagecreatetruecolor($new_w, $new_h);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $new_w, $new_h, $this->image_width, $this->image_height);
        $this->image_width = $new_w;
        $this->image_height = $new_h;
        $this->image = $new_image;
    }

    public function imagesave($image_type='jpeg', $image_file=NULL, $image_compress=100, $image_permiss='') {
        if($image_file==NULL) {
            switch($this->image_type) {
                case 'gif': header("Content-type: image/gif"); break;
                case 'jpeg': header("Content-type: image/jpeg"); break;
                case 'png': header("Content-type: image/png"); break;
            }
        }
        switch($this->image_type) {
            case 'gif': imagegif($this->image, $image_file); break;
            case 'jpeg': imagejpeg($this->image, $image_file, $image_compress); break;
            case 'png': imagepng($this->image, $image_file); break;
        }
        if($image_permiss != '') {
            chmod($image_file, $image_permiss);
        }
    }

    public function imageout() {
        imagedestroy($this->image);
    }

    public function __destruct() {

    }

}

