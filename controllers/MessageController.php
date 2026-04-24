<?php
require_once __DIR__ . '/../db.php';

function getMessagesWithMeta($limit, $offset, $userId = null) {
    global $conn;

    // 1) messages
    $stmt = $conn->prepare("
    SELECT * FROM messages 
    ORDER BY created_at DESC 
    LIMIT :limit OFFSET :offset
    ");
    
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    
    $stmt->execute();
    
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$messages) return [];

    // collect ids
    $ids = array_column($messages, 'id');
    $in  = implode(',', array_map('intval', $ids));

    // 2) reactions count
    $counts = [];
    $res = $conn->query("
        SELECT message_id,
               SUM(reaction='like') AS likes,
               SUM(reaction='dislike') AS dislikes
        FROM reactions
        WHERE message_id IN ($in)
        GROUP BY message_id
    ");
    while ($r = $res->fetch_assoc()) {
        $counts[$r['message_id']] = [
            'likes' => (int)$r['likes'],
            'dislikes' => (int)$r['dislikes']
        ];
    }

    // 3) my reaction
    $my = [];
    if ($userId) {
        $res = $conn->query("
            SELECT message_id, reaction
            FROM reactions
            WHERE user_id = " . (int)$userId . "
            AND message_id IN ($in)
        ");
        while ($r = $res->fetch_assoc()) {
            $my[$r['message_id']] = $r['reaction'];
        }
    }

    // 4) comments
    $commentsMap = [];

    $stmt = $conn->prepare("
        SELECT message_id, username, comment 
        FROM comments 
        WHERE message_id IN ($in)
        ORDER BY id ASC
    ");
    $stmt->execute();

    $cres = $stmt->get_result();

    while ($c = $cres->fetch_assoc()) {
        $mid = $c['message_id'];

        $commentsMap[$mid][] = [
            'username' => $c['username'],
            'comment'  => $c['comment']
        ];
    }

    // 5) merge
    foreach ($messages as &$m) {
        $id = $m['id'];

        $m['likes']      = $counts[$id]['likes']     ?? 0;
        $m['dislikes']   = $counts[$id]['dislikes']  ?? 0;
        $m['myReaction'] = $my[$id]                  ?? null;
        $m['comments']   = $commentsMap[$id]         ?? [];
    }

    return $messages;
}

/* OTHER FUNCTIONS BELOW ARE OK */
function countMessages() {
    global $conn;
    $res = $conn->query("SELECT COUNT(*) AS total FROM messages");
    return (int)$res->fetch_assoc()['total'];
}

function getPagination($limit) {
    global $conn;

    $result = $conn->query("SELECT COUNT(*) as total FROM messages");
    $totalRows = $result->fetch_assoc()['total'];

    return [
        'totalRows' => $totalRows,
        'totalPages' => ceil($totalRows / $limit)
    ];
}
