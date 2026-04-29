<?php
$db = new SQLite3(__DIR__ . '/portfolio.db');

$projects = [
    ['title' => 'AI Agentic Dashboard', 'cat' => 'Agentic AI', 'img' => 'assets/img/projects/ai-agent-1.png'],
    ['title' => 'Generative UX System', 'cat' => 'Generative AI', 'img' => 'assets/img/projects/gen-ux-1.png'],
    ['title' => 'Neuro-Interface Design', 'cat' => 'HCI', 'img' => 'assets/img/projects/hci-1.png'],
    ['title' => 'Enterprise AI Orchestrator', 'cat' => 'Agentic AI', 'img' => 'assets/img/projects/ai-agent-2.png'],
    ['title' => 'Dynamic Persona Generator', 'cat' => 'UX Research', 'img' => 'assets/img/projects/research-1.png'],
    ['title' => 'Self-Healing UI Components', 'cat' => 'Generative AI', 'img' => 'assets/img/projects/gen-ux-2.png'],
    ['title' => 'Predictive User Flow Engine', 'cat' => 'Behavioural UX', 'img' => 'assets/img/projects/behavior-1.png'],
    ['title' => 'Multimodal AI Workspace', 'cat' => 'Product Design', 'img' => 'assets/img/projects/product-1.png'],
    ['title' => 'Cognitive Load Optimizer', 'cat' => 'HCI', 'img' => 'assets/img/projects/hci-2.png'],
    ['title' => 'Agentic Workflow Builder', 'cat' => 'Agentic AI', 'img' => 'assets/img/projects/ai-agent-3.png'],
    // ... adding more to reach 50
];

// Generate more to reach 50
$categories = ['Agentic AI', 'Generative AI', 'UX Architecture', 'Product Design', 'HCI', 'Behavioural UX', 'UX Research'];
for($i = count($projects); $i < 50; $i++) {
    $cat = $categories[array_rand($categories)];
    $projects[] = [
        'title' => $cat . ' Project ' . ($i + 1),
        'cat' => $cat,
        'img' => 'assets/img/projects/project-' . (($i % 6) + 1) . '.png'
    ];
}

$db->exec("DELETE FROM projects"); // Clear existing
$stmt = $db->prepare("INSERT INTO projects (title, category, image, link) VALUES (:title, :cat, :img, '#')");

foreach($projects as $p) {
    $stmt->bindValue(':title', $p['title'], SQLITE3_TEXT);
    $stmt->bindValue(':cat', $p['cat'], SQLITE3_TEXT);
    $stmt->bindValue(':img', $p['img'], SQLITE3_TEXT);
    $stmt->execute();
}

echo "50 High-Performance AI & UX Projects seeded successfully.";
?>
