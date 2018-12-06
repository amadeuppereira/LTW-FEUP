<?php
require_once __DIR__ . '/../db/db.php';

function keyfy(array $array, string $key) {
    $object = [];

    foreach ($array as $el) {
        $object[$el[$key]] = $el;
    }

    return $object;
}

class APIEntity {
    protected static function cast(array $fetch) {
        $object = [];

        foreach ($fetch as $key => $value) {
            switch ($key) {
            // IDs
            case 'userid':
            case 'entityid':
            case 'storyid':
            case 'commentid':
            case 'authorid':
            case 'creatorid':
            case 'channelid':
            case 'parentid':
                $object[$key] = (integer)$value;
                break;

            // Votes
            case 'upvotes':
            case 'downvotes':
            case 'votes':
                $object[$key] = (integer)$value;
                break;

            // Timestamps
            case 'createdat':
            case 'updatedat':
            case 'savedat':
            case 'date':
            case 'timestamp':
                $object[$key] = (integer)$value;
                break;

            // Text
            case 'name':
            case 'username':
            case 'authorname':
            case 'channelname':
            case 'email':
            case 'hash':
            case 'title':
            case 'type':
            case 'kind':
            case 'vote':
            case 'storyTitle':
            case 'storyType':
            case 'content':
                $object[$key] = $value;
                break;

            default:
                throw new Error("Unhandled APIEntity cast case: < $key >");
            }
        }

        return $object;
    }

    protected static function fetch(PDOStatement &$stmt) {
        $fetch = $stmt->fetch();
        if ($fetch == null || $fetch === false) return $fetch;

        return static::cast($fetch);
    }

    protected static function fetchAll(PDOStatement &$stmt) {
        $fetches = $stmt->fetchAll();
        if ($fetches == null || $fetches === false) return $fetches;

        $object = [];
        
        foreach ($fetches as $fetch) {
            $casted = static::cast($fetch);

            array_push($object, $casted);
        }

        return $object;
    }

    protected static function execute(PDOStatement &$stmt, array $args = null) {
        if (!$args) {
            return $stmt->execute();
        } else {
            return $stmt->execute($args);
        }
    }
}
?>
