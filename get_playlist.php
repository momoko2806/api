<?php
/*
    Version: 1.1
    Author: HKLCF
    Copyright: HKLCF
    Last Modified: 18/08/2015
*/
$video_id = $_GET['video_id'];
$mytv_video_api = "http://api.mytv.tvb.com/rest_search_api/data_video/format/json/id/{$video_id}";
$result = json_decode(file_get_contents($mytv_video_api), true);

// $result['content'][0]['urls']['3808000']; //video_bitrate:3808000 1080p
// $result['content'][0]['urls']['2404000']; //video_bitrate:2404000 720p
// $result['content'][0]['urls']['1404000']; //video_bitrate:1404000 720p
// $result['content'][0]['urls']['904000']; //video_bitrate:904000 720p
// $result['content'][0]['urls']['752000']; //video_bitrate:752000 480p
// $result['content'][0]['urls']['452000']; //video_bitrate:452000 360p
// $result['content'][0]['urls']['352000']; //video_bitrate:352000 270p
// $result['content'][0]['urls']['176000']; //video_bitrate:176000 240p
// $result['content'][0]['urls']['76000']; //video_bitrate:76000 180p
if(empty($result['content'][0]['urls']['3808000'])) {
    if(empty($result['content'][0]['urls']['2404000'])) {
        if(empty($result['content'][0]['urls']['1404000'])) {
            if(empty($result['content'][0]['urls']['904000'])) {
                if(empty($result['content'][0]['urls']['752000'])) {
                    if(empty($result['content'][0]['urls']['452000'])) {
                        if(empty($result['content'][0]['urls']['352000'])) {
                            if(empty($result['content'][0]['urls']['176000'])) {
                                $result = $result['content'][0]['urls']['76000'];
                            } else {
                                $result = $result['content'][0]['urls']['176000'];
                            }
                        } else {
                            $result = $result['content'][0]['urls']['352000'];
                        }
                    } else {
                        $result = $result['content'][0]['urls']['452000'];
                    }
                } else {
                    $result = $result['content'][0]['urls']['752000'];
                }
            } else {
                $result = $result['content'][0]['urls']['904000'];
            }
        } else {
            $result = $result['content'][0]['urls']['1404000'];
        }
    } else {
        $result = $result['content'][0]['urls']['2404000'];
    }
} else {
    $result = $result['content'][0]['urls']['3808000'];
}

if(empty($result)) {
    echo 'Playlist not found!';
} else {
    if(preg_match('/^[0-9]{2}/', $result)) { // mytv
        preg_match('/[0-9]{12}/', $result, $path);
        $path = preg_replace('/^[0-9]{6}/', '', $path[0]);
        $path = preg_replace('/[0-9]{2}$/', '', $path);
        $path = $path + 1;
        $result = preg_replace('/[\/]([0-9]{4})[\/]([0-9]{6})/', '/'.$path, $result);
        $result = str_replace('smil', 'mp4', $result);
        echo "http://streaming.tvb.com/vi/_definst_/{$result}/playlist.m3u8";
    } else { //gotv
        $result = str_replace('smil', 'mp4', $result);
        $gotv_video_api = "http://token.tvb.com/stream/vod/rtmps/{$result}?feed";
        $result = json_decode(file_get_contents($gotv_video_api), true);
        $result = str_replace('rtmps', 'http', $result['url']);
        $result = str_replace('.mp4', '.mp4/playlist.m3u8', $result);
        echo $result;
    }
}
?>
