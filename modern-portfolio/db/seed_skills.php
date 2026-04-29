<?php
$db = new SQLite3(__DIR__ . '/portfolio.db');

// Create Skills Table
$db->exec("CREATE TABLE IF NOT EXISTS skills (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    icon_url TEXT,
    category TEXT
)");

$skills = [
    ['name' => 'OpenAI', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/openai.svg'],
    ['name' => 'Anthropic', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/anthropic.svg'],
    ['name' => 'Figma', 'cat' => 'Design', 'icon' => 'https://svgl.app/library/figma.svg'],
    ['name' => 'Adobe', 'cat' => 'Design', 'icon' => 'https://svgl.app/library/adobe.svg'],
    ['name' => 'Next.js', 'cat' => 'Development', 'icon' => 'https://svgl.app/library/nextjs_icon_dark.svg'],
    ['name' => 'React', 'cat' => 'Development', 'icon' => 'https://svgl.app/library/react.svg'],
    ['name' => 'TypeScript', 'cat' => 'Development', 'icon' => 'https://svgl.app/library/typescript.svg'],
    ['name' => 'Tailwind', 'cat' => 'Design', 'icon' => 'https://svgl.app/library/tailwindcss.svg'],
    ['name' => 'Python', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/python.svg'],
    ['name' => 'LangChain', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/langchain.svg'],
    ['name' => 'Supabase', 'cat' => 'Backend', 'icon' => 'https://svgl.app/library/supabase.svg'],
    ['name' => 'Vercel', 'cat' => 'Deployment', 'icon' => 'https://svgl.app/library/vercel_icon_dark.svg'],
    ['name' => 'GitHub', 'cat' => 'Tools', 'icon' => 'https://svgl.app/library/github_icon_dark.svg'],
    ['name' => 'Hugging Face', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/huggingface.svg'],
];

$db->exec("DELETE FROM skills");
$stmt = $db->prepare("INSERT INTO skills (name, category, icon_url) VALUES (:name, :cat, :icon)");

foreach($skills as $s) {
    $stmt->bindValue(':name', $s['name'], SQLITE3_TEXT);
    $stmt->bindValue(':cat', $s['cat'], SQLITE3_TEXT);
    $stmt->bindValue(':icon', $s['icon'], SQLITE3_TEXT);
    $stmt->execute();
}

echo "Premium Skill Icons from svgl.app seeded successfully.";
?>
