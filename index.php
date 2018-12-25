<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once('./LINEBotTiny.php');

$channelAccessToken = getenv('LINE_CHANNEL_ACCESS_TOKEN');
$channelSecret = getenv('LINE_CHANNEL_SECRET');

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event){
    switch ($event['type']){
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    $search = array('🐶','🐱');
                    $replace = array('🐱','🐶');
                    $message['text'] = str_replace($search, $replace, $message['text']);

                    switch ($message['text']){
                        case '🐰':
                              $message['text'] = 'うさ。。';
                              break;
                        default:
                              $message['text'] = $message['text'];
                              break;
                    }
                    $client->replyMessage([
                        'replyToken' => $event['replyToken'],
                            'messages' => [
                            [
                                'type' => 'text',
                                'text' => $message['text']
                            ]
                        ]
                    ]);
                    break;
                case 'sticker':
                    $client->replyMessage([
                        'replyToken' => $event['replyToken'],
                        'messages' => [
                            [
                                'type' => 'sticker',
                                'packageId' => '2',
                                'stickerId' => '40'
                            ]
                        ]
                    ]);
                    break;
                default:
                    error_log('Unsupported message type: ' . $message['type']);
                    break;
            }
            break;
        default:
            error_log('Unsupported event type: ' . $event['type']);
            break;
    }
};
