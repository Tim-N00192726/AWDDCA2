<?php
function get_words($text, $count = 10) {
  return implode(' ', array_slice(explode(' ', $text), 0, $count));
}

function old($key, $default=null) {
  global $request;
  if ($request->session()->has("flash_data")) {
    $data = $request->session()->get("flash_data");
    if (is_array($data) && array_key_exists($key, $data)) {
      return $data[$key];
    }
  }
  else {
    return $default;
  }
}
?>
