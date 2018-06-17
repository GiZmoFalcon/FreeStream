<?php

////////////////////////////////////////////////////////////////
// qBittorrent_login() returns an associative array
// as $response["cookie"], $response["success"] and 
// $response["debug"].
///////////////////////////////////////////////////////////////
function qBittorrent_login() {

    $post = [
        'username' => 'admin',
        'password' => 'admin123'
    ];

    $ch = curl_init('http://localhost:8080/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    $server_output = curl_exec($ch);
    if (!curl_errno($ch)) {
        switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            case 200: $response["success"] = true;
                $response["debug"] = "got HTTP 200 response code";
                break;
            default:
                //return a failed response if the server is ON, but returned a different HTTP response code
                $response["success"] = false;
                $response["debug"] = "got HTTP response code other than 200";
                return $response;
        }
    } else {
        //return a failed response if there is some error in cURL
        $response["debug"] = "Error in cURL. You might be hitting a different address to access qBittorrent";
        $response["success"] = false;
        return $response;
    }

    //separate HTTP header and body from the HTTP response
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($server_output, 0, $header_size);
    $body = substr($server_output, $header_size);

    //check if the HTTP body says "Ok.". If it did, then login was successful and we will have a cookie in HTTP header!
    if ($body == "Ok.") {
        $response["debug"] = "login success";
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $matches);
        $response["cookie"] = $matches[1][0];
        return $response;
    } else {
        //else return a failed response. The credentials might not have been correct
        $response["debug"] = "login failed. You might be using wrong credentials -- Or -- your IP might have been banned. Check qBittorrent client directly.";
        $response["success"] = false;
        return $response;
    }
}

////////////////////////////////////////////////////////////////
// start_download() starts downloading a torrent. 
// It returns true on success and false on failed.
// Note: If the torrent hash is 'wrong', it will still return true.
// But, if the torrent hash is 'invalid', it will return false. 
///////////////////////////////////////////////////////////////
function start_download($cookie, $torrent_hash, $path) {
    $ch = curl_init('http://localhost:8080/command/download');
    $headers = [
        "Host: 127.0.0.1",
        "Cookie: $cookie"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('urls' => "$torrent_hash", 'savepath' => "$path", 'sequentialDownload' => "true"));

    $server_output = curl_exec($ch);
    if ($server_output === "Ok.") {
        return true;
    } else {
        return false;
    }
}

////////////////////////////////////////////////////////////////
// pause_download() pause the downloading of a particular torrent. 
// Here, you can't check if the operation was success or not,
// because qBittorrent return HTTP status code 200 on either 
// of the case. So you can say that this function returns void. 
///////////////////////////////////////////////////////////////
function pause_download($cookie, $torrent_hash) {
    $ch = curl_init('http://localhost:8080/command/pause');
    $headers = [
        "Host: 127.0.0.1",
        "Cookie: $cookie"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('hash' => "$torrent_hash"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
}

////////////////////////////////////////////////////////////////
// resume_download() resumes the downloading of a particular paused 
// torrent. Here, you can't check if the operation was success or not,
// because qBittorrent return HTTP status code 200 on either 
// of the case. So you can say that this function returns void. 
///////////////////////////////////////////////////////////////
function resume_download($cookie, $torrent_hash) {
    $ch = curl_init('http://localhost:8080/command/resume');
    $headers = [
        "Host: 127.0.0.1",
        "Cookie: $cookie"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('hash' => "$torrent_hash"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
}

////////////////////////////////////////////////////////////////
// get_torrent_info() gives the current stats of a given torrent download. 
// It returns an associative array '$torrent_info'
// $torrent_info["down_speed"] ---> gives the current average download speed
// $torrent_info["remaining_time"] ---> gives the remaining time for the download as estimated
// $torrent_info["current_downloaded"] ---> gives the current downloaded amount
// $torrent_info["total_size"] ---> gives the total file size that is being downloaded
// $torrent_info["percent_dl_completed"] ---> gives the percentage of completion of download
// $torrent_info["download_completed"] ---> gives true or false based on whether the download has
// been completed or not.
///////////////////////////////////////////////////////////////
function get_torrent_info($cookie, $torrent_hash) {
    $ch = curl_init("http://localhost:8080/query/propertiesGeneral/$torrent_hash");
    $headers = [
        "Host: 127.0.0.1",
        "Cookie: $cookie"
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    echo "About to display the result querying torrent info:\n\n";

    $json = curl_exec($ch);

    $torrent_arr = json_decode($json, true);
    $dl_speed = $torrent_arr["dl_speed"]; //    B/s
    $dl_speed = $dl_speed / 1024; //    KB/s
    $dl_speed_text = $dl_speed . " KB/s";
    if ($dl_speed >= 1024) {
        $dl_speed = $dl_speed / 1024; //     MB/s
        $dl_speed_text = $dl_speed . " MB/s";
    }
    $torrent_info["down_speed"] = $dl_speed_text;

    $remaining_time = $torrent_arr["eta"]; //  seconds
    $remaining_time_text = $remaining_time . " secs";
    if ($remaining_time >= 60) {
        $remaining_time = $remaining_time / 60; //   minutes
        $remaining_time_text = $remaining_time . " mins";
    }
    if ($remaining_time >= 3600) {
        $remaining_time = $remaining_time / 3600; //   minutes
        $remaining_time_text = $remaining_time . " hrs";
    }
    $torrent_info["remaining_time"] = $remaining_time_text;

    $dl = $torrent_arr["total_downloaded"]; //    B
    $dl = $dl / 1024; //    KB
    $dl_text = $dl . " KB";
    if ($dl >= 1024) {
        $dl = $dl / 1024; //     MB
        $dl_text = $dl . " MB";
    }
    if ($dl >= 1024) {
        $dl = $dl / 1024; //     GB
        $dl_text = $dl . " GB";
    }
    $torrent_info["current_downloaded"] = $dl_text;


    $size = $torrent_arr["total_size"]; //    B
    $size = $size / 1024; //    KB
    $size_text = $size . " KB";
    if ($size >= 1024) {
        $size = $size / 1024; //     MB
        $size_text = $size . " MB";
    }
    if ($size >= 1024) {
        $size = $size / 1024; //     GB
        $size_text = $size . " GB";
    }
    $torrent_info["total_size"] = $size_text;


    $percent_dl_completed = ($torrent_arr["total_downloaded"] / $torrent_arr["total_size"]) * 100;
    $torrent_info["percent_dl_completed"] = round($percent_dl_completed, 2) . "%";

    if ($percent_dl_completed == "100") {
        $torrent_info["download_completed"] = true;
    } else {
        $torrent_info["download_completed"] = false;
    }


    return $torrent_info;
}

//echo download_torrent_file("https://yts.am/torrent/download/B0BD2B254110574E5EFB9D376201EC584B1D26B0");
//add_torrent(qBittorrent_login(), " ");
//https://archive.org/download/Sintel.2010.1080p/Sintel.2010.1080p_archive.torrent

$res = qBittorrent_login();
echo $res["debug"] . "\n";
if ($res["success"]) {
    $cookie = $res["cookie"];
    echo "cookie: $cookie \n";
}

$hash = "b0bd2b254110574e5efb9d376201ec584b1d26b0";
//echo "after download start: ".start_download($cookie,"78ff8193d061a0ab4e2d0e1d1f66bdc727b0fd1d","/home/nandan/Desktop");
if (start_download($cookie, $hash, "/home/nandan/Desktop")) {
    echo "download started!\n";
} else {
    echo "failed";
}

sleep(8);
pause_download($cookie, $hash);
sleep(8);
resume_download($cookie, $hash);
//pause_download($cookie, "78ff8193d061a0ab4e2d0e1d1f66bdc727b0fd1d");
$info = get_torrent_info($cookie, $hash);
echo "download speed :" . $info["down_speed"] . "\n";
echo "remaining time :" . $info["remaining_time"] . "\n";
echo "current downloaded :" . $info["current_downloaded"] . "\n";
echo "total size :" . $info["total_size"] . "\n";
echo "% download :" . $info["percent_dl_completed"] . "\n";
echo "download complete :" . $info["download_completed"] . "\n\n\n";
//resume_download($cookie, "78ff8193d061a0ab4e2d0e1d1f66bdc727b0fd1d");
while (!$info["download_completed"]) {
    sleep(2);
    echo "download speed :" . $info["down_speed"] . "\n";
    echo "remaining time :" . $info["remaining_time"] . "\n";
    echo "current downloaded :" . $info["current_downloaded"] . "\n";
    echo "total size :" . $info["total_size"] . "\n";
    echo "% download :" . $info["percent_dl_completed"] . "\n";
    echo "download complete :" . $info["download_completed"] . "\n\n\n";
    $info = get_torrent_info($cookie, $hash);
}
/*
  sleep(30);
  pause_download($cookie,"a033b0625dc2124a9af0e87e75403f2fe9bebb0d");
  sleep(30);
  resume_download($cookie,"a033b0625dc2124a9af0e87e75403f2fe9bebb0d");
  sleep(30);
  pause_download($cookie,"a033b0625dc2124a9af0e87e75403f2fe9bebb0d");
 */
?>
