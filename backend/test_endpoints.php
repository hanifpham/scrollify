<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create testing users
$user1 = \App\Models\User::firstOrCreate(['email' => 'test1@example.com'], ['name' => 'User 1', 'password' => bcrypt('password')]);
$user2 = \App\Models\User::firstOrCreate(['email' => 'test2@example.com'], ['name' => 'User 2', 'password' => bcrypt('password')]);
$token1 = $user1->createToken('test')->plainTextToken;
$token2 = $user2->createToken('test')->plainTextToken;
$mangaId = 'a96676e5-8ae2-425e-b549-7f15dd34a6d8'; // Solo Leveling

function sendRequest($kernel, $method, $uri, $token = null, $data = []) {
    $server = ['HTTP_ACCEPT' => 'application/json'];
    if ($token) $server['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
    $request = Illuminate\Http\Request::create($uri, $method, $data, [], [], $server);
    if (!empty($data) && $method !== 'GET') {
        $request->headers->set('Content-Type', 'application/json');
        $request = Illuminate\Http\Request::create($uri, $method, [], [], [], $server, json_encode($data));
    }
    $response = $kernel->handle($request);
    $kernel->terminate($request, $response);
    return ['status' => $response->getStatusCode(), 'content' => json_decode($response->getContent(), true)];
}

// 1. POST /api/bookmarks tanpa token -> 401
$res1 = sendRequest($kernel, 'POST', '/api/bookmarks', null, ['manga_id' => $mangaId]);
echo "1. POST without token: " . $res1['status'] . "\n";

// 2. POST /api/bookmarks dengan token valid + manga_id asli -> 201
\App\Models\Bookmark::where('user_id', $user1->id)->delete();
$res2 = sendRequest($kernel, 'POST', '/api/bookmarks', $token1, ['manga_id' => $mangaId]);
echo "2. POST with token: " . $res2['status'] . "\n";

// 3. POST /api/bookmarks dengan manga_id yang sama lagi (user sama) -> 409
$res3 = sendRequest($kernel, 'POST', '/api/bookmarks', $token1, ['manga_id' => $mangaId]);
echo "3. POST duplicate: " . $res3['status'] . "\n";

// 4. GET /api/bookmarks hanya menampilkan bookmark milik user yang login
sendRequest($kernel, 'POST', '/api/bookmarks', $token2, ['manga_id' => '02951e18-62a2-4a0b-85cd-fb5b5ec18b87']);
$res4_1 = sendRequest($kernel, 'GET', '/api/bookmarks', $token1);
$res4_2 = sendRequest($kernel, 'GET', '/api/bookmarks', $token2);
echo "4. GET user1 bookmarks count: " . count($res4_1['content']['data'] ?? []) . " mangaId: " . ($res4_1['content']['data'][0]['id'] ?? 'none') . "\n";
echo "   GET user2 bookmarks count: " . count($res4_2['content']['data'] ?? []) . " mangaId: " . ($res4_2['content']['data'][0]['id'] ?? 'none') . "\n";

// 5. DELETE /api/bookmarks/{mangaId} berhasil menghapus
$res5_1 = sendRequest($kernel, 'DELETE', "/api/bookmarks/{$mangaId}", $token1);
$res5_2 = sendRequest($kernel, 'GET', '/api/bookmarks', $token1);
echo "5. DELETE status: " . $res5_1['status'] . " | GET count after: " . count($res5_2['content']['data'] ?? []) . "\n";

// 6. PUT /api/reading-history dipanggil 2x
\App\Models\ReadingHistory::where('user_id', $user1->id)->delete();
$historyData = ['manga_id' => $mangaId, 'chapter_id' => '779f70d0-04d6-46e4-9d38-f6f740cfd02a', 'chapter_number' => '1', 'last_page_read' => 5];
$res6_1 = sendRequest($kernel, 'PUT', '/api/reading-history', $token1, $historyData);
sleep(1);
$historyData['last_page_read'] = 10;
$res6_2 = sendRequest($kernel, 'PUT', '/api/reading-history', $token1, $historyData);
$dbCount = \App\Models\ReadingHistory::where('user_id', $user1->id)->count();
echo "6. PUT 1 status: " . $res6_1['status'] . " | PUT 2 status: " . $res6_2['status'] . " | DB rows: " . $dbCount . "\n";

// 7. GET /api/schedules TANPA token tetap berhasil 200
$res7 = sendRequest($kernel, 'GET', '/api/schedules');
echo "7. GET schedules without token: " . $res7['status'] . "\n";

// 8. GET /api/schedules?day=monday
$res8 = sendRequest($kernel, 'GET', '/api/schedules?day=monday');
echo "8. GET schedules?day=monday: " . $res8['status'] . " | has monday key: " . (isset($res8['content']['data']['monday']) ? 'yes' : 'no') . "\n";
