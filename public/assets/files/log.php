<?php
$logs = glob('./logs/*');
rsort($logs);

function filesize_formatted($path)
{
  $size = filesize($path);
  $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
  $power = $size > 0 ? floor(log($size, 1024)) : 0;
  return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

function get_filename($path)
{
  $file = pathinfo($path);
  return $file['basename'];
}
?>
<html>

<head>
  <title>Index of Logs</title>
</head>

<body>
  <h1>Index of Logs</h1>
  <table>
    <tr>
      <th valign="top">&nbsp;</th>
      <th>Name</th>
      <th>Last modified</th>
      <th>Size</th>
    </tr>
    <tr>
      <th colspan="5">
        <hr>
      </th>
    </tr>
    <?php foreach ($logs as $log) : ?>
      <tr>
        <td valign="top">&nbsp;</td>
        <td><a href="<?= $log ?>"><?= get_filename($log) ?></a> </td>
        <td align="right"><?= date('Y-m-d H:i:s', filectime($log)) ?></td>
        <td align="right"><?= filesize_formatted($log) ?></td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <th colspan="5">
        <hr>
      </th>
    </tr>
  </table>
</body>

</html>