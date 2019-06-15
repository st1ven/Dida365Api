<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');

include 'customCurl.php';

function login_dida365($username, $password) {
    $curlObj = CustomCurl::init('https://api.dida365.com/api/v2/user/signon?wc=true&remember=true', 'post')
                ->set('postFields', ['username' => $username, 'password' => $password])
                ->set('postType', 'json')
                ->exec();
    if ($curlObj->getStatus()) {
        $data = json_decode($curlObj->getBody(), true);
        $token = $data['token'];
        return empty($token) ? false : $token;
    } else {
        var_dump($curlObj->getCurlErrNo());
    }
}

function action_dida365($token, $action) {
    $curlObj = CustomCurl::init('https://api.dida365.com/api/v2/'.$action)
                ->setCookie('t', $token)
                ->exec();
    if ($curlObj->getStatus()) {
        return $curlObj->getBody();
    } else {
        var_dump($curlObj->getCurlErrNo());
    }
}

$token = login_dida365('email', 'password');

if ($token) {
    switch (isset($_GET['action']) ? $_GET['action'] : 'default') {
        case 'trash':
            echo action_dida365($token, 'project/all/trash/pagination');
            break;

        case 'completed':
            echo action_dida365($token, 'project/all/completed');
            break;

        case 'list':
            echo action_dida365($token, 'batch/check/0');
            break;

        case 'comments':
            $projectId = isset($_GET['project']) ? $_GET['project'] : null;
            $taskId = isset($_GET['task']) ? $_GET['task'] : null;
            if (empty($projectId) || empty($taskId)) {
                echo json_encode(['error' => true, 'message' => 'projectId与taskId不能为空']);
                break;
            }
            echo action_dida365($token, 'project/'.$projectId.'/task/'.$taskId.'/comments');
            break;

        default:
            echo json_encode([
                'error' => true,
                'message' => [
                    'list' => [
                        'info' => '获取任务组与任务列表',
                        'method' => 'GET',
                        'params' => 'action=list'
                    ],
                    'comments' => [
                        'info' => '获取任务清单的评论内容',
                        'method' => 'GET',
                        'params' => 'action=comments&project={projectId}&task={taskId}'
                    ],
                    'completed' => [
                        'info' => '获取已完成的任务清单',
                        'method' => 'GET',
                        'params' => 'action=completed'
                    ],
                    'trash' => [
                        'info' => '获取垃圾箱内的任务清单',
                        'method' => 'GET',
                        'params' => 'action=trash'
                    ]
                ]
            ]);
            break;
    }
}
