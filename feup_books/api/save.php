<?php
require_once __DIR__ . '/../../api/api.php';
require_once API::entity('save');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'save';

$methods = ['GET', 'PUT', 'DELETE'];

$actions = [
    'put'               => ['PUT', ['entityid', 'userid']],

    'get-comment'       => ['GET', ['commentid'], [], ['limit', 'offset']],
    'get-story'         => ['GET', ['storyid'], [], ['limit', 'offset']],
    'get-entity'        => ['GET', ['entityid'], [], ['limit', 'offset']],
    'get-user-comments' => ['GET', ['userid', 'comments'], [], ['limit', 'offset']],
    'get-user-stories'  => ['GET', ['userid', 'stories'], [], ['limit', 'offset']],
    'get-user-all'      => ['GET', ['userid', 'all'], [], ['limit', 'offset']],
    'get-comments'      => ['GET', ['comments'], [], ['limit', 'offset']],
    'get-stories'       => ['GET', ['stories'], [], ['limit', 'offset']],
    'get-all'           => ['GET', ['all'], [], ['limit', 'offset']],

    'delete-id'         => ['DELETE', ['entityid', 'userid']],
    'delete-user'       => ['DELETE', ['userid', 'all']],
    'delete-entity'     => ['DELETE', ['entityid', 'all']],
    'delete-all'        => ['DELETE', ['all']]
];

/**
 * 1.2. LOAD request description variables
 */
$method = HTTPRequest::method($methods);

$action = HTTPRequest::action($resource, $actions);

$args = API::cast($_GET);

/**
 * 2. GET: Check query parameter identifying resources
 * SAVE: entityid, commentid, storyid, userid, comments, stories, all
 */
// entityid
if (API::gotargs('entityid')) {
    $entityid = $args['entityid'];

    $entity = Entity::read($entityid);

    if (!$entity) {
        HTTPResponse::notFound("Entity with id $entityid");
    }
}
// commentid
if (API::gotargs('commentid')) {
    $commentid = $args['commentid'];

    $comment = Comment::read($commentid);

    if (!$comment) {
        HTTPResponse::notFound("Comment with id $commentid");
    }

    $authorid = $comment['authorid'];
}
// storyid
if (API::gotargs('storyid')) {
    $storyid = $args['storyid'];

    $story = Story::read($storyid);

    if (!$story) {
        HTTPResponse::notFound("Story with id $storyid");
    }

    $authorid = $story['authorid'];
}
// userid
if (API::gotargs('userid')) {
    $userid = $args['userid'];

    $user = User::read($userid);

    if (!$user) {
        HTTPResponse::notFound("User with id $userid");
    }
}

/**
 * 3. ANSWER: HTTPResponse
 */
// PUT
if ($action === 'put') {
    $auth = Auth::demandLevel('authid', $userid);

    $count = Save::create($entityid, $userid);

    $data = [
        'count' => $count,
        'entityid' => $entityid,
        'userid' => $userid
    ];

    HTTPResponse::created("User $userid saved entity $entityid", $data);
}

// GET
if ($action === 'get-comment') {
    $auth = Auth::demandLevel('admin');

    $saves = Save::getComment($commentid, $args);

    HTTPResponse::ok("All saves of comment $commentid", $saves);
}

if ($action === 'get-story') {
    $auth = Auth::demandLevel('admin');

    $saves = Save::getStory($storyid, $args);

    HTTPResponse::ok("All saves of story $storyid", $saves);
}

if ($action === 'get-entity') {
    $auth = Auth::demandLevel('admin');

    $saves = Save::getEntity($entityid, $args);

    HTTPResponse::ok("All saves of entity $entityid", $saves);
}

if ($action === 'get-user-comments') {
    $auth = Auth::demandLevel('authid', $userid);

    $saves = Save::getUserComments($userid, $args);

    HTTPResponse::ok("All saved comments of user $userid", $saves);
}

if ($action === 'get-user-stories') {
    $auth = Auth::demandLevel('authid', $userid);

    $saves = Save::getUserStories($userid, $args);

    HTTPResponse::ok("All saved stories of user $userid", $saves);
}

if ($action === 'get-user-all') {
    $auth = Auth::demandLevel('authid', $userid);

    $saves = Save::getUserAll($userid, $args);

    HTTPResponse::ok("All saves of user $userid", $saves);
}

if ($action === 'get-comments') {
    $auth = Auth::demandLevel('admin');

    $saves = Save::readAllComments($args);

    HTTPResponse::ok("All comment saves", $saves);
}

if ($action === 'get-stories') {
    $auth = Auth::demandLevel('admin');

    $saves = Save::readAllStories($args);

    HTTPResponse::ok("All story saves", $saves);
}

if ($action === 'get-all') {
    $auth = Auth::demandLevel('admin');

    $saves = Save::readAll($args);

    HTTPResponse::ok("All saves", $saves);
}

// DELETE
if ($action === 'delete-id') {
    $auth = Auth::demandLevel('authid', $userid);

    $count = Save::delete($entityid, $userid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted user $userid save of entity $entityid", $data);
}

if ($action === 'delete-user') {
    $auth = Auth::demandLevel('authid', $userid);

    $count = Save::deleteUser($userid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted all user $userid saves", $data);
}

if ($action === 'delete-entity') {
    $auth = Auth::demandLevel('admin');

    $count = Save::deleteEntity($entityid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted all saves of entity $entityid", $data);
}

if ($action === 'delete-all') {
    $auth = Auth::demandLevel('admin');

    $count = Save::deleteAll();

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted all saves", $data);
}
?>
