<?php
//Load FUSE, if it isn't already loaded by php.ini
if (!extension_loaded("fuse"))
    dl("fuse.so");
error_reporting(E_ALL);

class Sample2Fuse extends Fuse {
  public function getdir($path, &$retval) { 
    if ($path != "/") {
      return -FUSE_ENOENT;
    }
    $retval["."] = array('type' => FUSE_DT_DIR);
    $retval[".."] = array('type' => FUSE_DT_DIR);
    $retval["test.txt"] = array('type' => FUSE_DT_REG);
    return 0;
  } 

  public function getattr($path, &$st) { 
    $st['dev'] = 0;
    $st['ino'] = 0;
    $st['mode'] = 0;
    $st['nlink'] = 0;
    $st['uid'] = 0;
    $st['gid'] = 0;
    $st['rdev'] = 0;
    $st['size'] = 0;
    $st['atime'] = 0;
    $st['mtime'] = 0;
    $st['ctime'] = 0;
    $st['blksize'] = 0;
    $st['blocks'] = 0;

    if ($path == "/") {
      $st['mode'] = FUSE_S_IFDIR | 0775; 
      $st['nlink'] = 3;
      $st['size'] = 0;
    } else if ($path == "/test.txt") {
      $st['mode'] = FUSE_S_IFREG | 0664; 
      $st['nlink'] = 1;
      $st['size'] = 12; 
    }

    return 0;
  }
  public function open($path, $mode) {
    return 1;
  }

  public function read($path, $fh, $offset, $buf_len, &$buf) {
    $buf = "hello world\n";
    return strlen($buf);
  }

  public function release($path, $fh) {
    return 0;
  }
}

$fuse = new Sample2Fuse();
$fuse->fuse_main($argc,$argv);
