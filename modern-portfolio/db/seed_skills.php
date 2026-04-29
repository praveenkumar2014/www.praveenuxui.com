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
    // svgl.app changed some filenames; use current, non-404 variants
    ['name' => 'Anthropic', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/anthropic_white.svg'],
    ['name' => 'Cohere', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/cohere.svg'],
    ['name' => 'Groq', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/groq.svg'],
    ['name' => 'Ollama', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/ollama_dark.svg'],
    ['name' => 'Together AI', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/togetherai_dark.svg'],
    ['name' => 'Figma', 'cat' => 'Design', 'icon' => 'https://svgl.app/library/figma.svg'],
    ['name' => 'Adobe', 'cat' => 'Design', 'icon' => 'https://svgl.app/library/adobe.svg'],
    ['name' => 'Blender', 'cat' => 'Design', 'icon' => 'https://svgl.app/library/blender.svg'],
    ['name' => 'daisyUI', 'cat' => 'Design', 'icon' => 'https://svgl.app/library/daisyui.svg'],
    ['name' => 'Next.js', 'cat' => 'Development', 'icon' => 'https://svgl.app/library/nextjs_icon_dark.svg'],
    ['name' => 'React', 'cat' => 'Development', 'icon' => 'https://svgl.app/library/react_dark.svg'],
    ['name' => 'TypeScript', 'cat' => 'Development', 'icon' => 'https://svgl.app/library/typescript.svg'],
    ['name' => 'Preact', 'cat' => 'Development', 'icon' => 'https://svgl.app/library/preact.svg'],
    ['name' => 'Svelte', 'cat' => 'Development', 'icon' => 'https://svgl.app/library/svelte.svg'],
    ['name' => 'Vue', 'cat' => 'Development', 'icon' => 'https://svgl.app/library/vue.svg'],
    ['name' => 'Vuetify', 'cat' => 'Development', 'icon' => 'https://svgl.app/library/vuetify.svg'],
    ['name' => 'Tailwind', 'cat' => 'Design', 'icon' => 'https://svgl.app/library/tailwindcss.svg'],
    ['name' => 'Python', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/python.svg'],
    // Not present on svgl.app currently; use SimpleIcons
    ['name' => 'LangChain', 'cat' => 'AI & ML', 'icon' => 'https://cdn.simpleicons.org/langchain'],
    ['name' => 'LangGraph', 'cat' => 'AI & ML', 'icon' => 'https://cdn.simpleicons.org/langchain'],
    ['name' => 'LlamaIndex', 'cat' => 'AI & ML', 'icon' => 'https://cdn.simpleicons.org/llamaindex'],
    ['name' => 'CrewAI', 'cat' => 'AI & ML', 'icon' => 'https://cdn.simpleicons.org/openai'],
    ['name' => 'AutoGen', 'cat' => 'AI & ML', 'icon' => 'https://cdn.simpleicons.org/microsoft'],
    ['name' => 'RAG Systems', 'cat' => 'AI & ML', 'icon' => 'https://cdn.simpleicons.org/openai'],
    ['name' => 'MCP Tooling', 'cat' => 'AI & ML', 'icon' => 'assets/img/brands/ai.svg'],
    ['name' => 'LLM Chatbots', 'cat' => 'AI & ML', 'icon' => 'assets/img/brands/ai.svg'],
    ['name' => 'Agentic Workflows', 'cat' => 'AI & ML', 'icon' => 'https://cdn.simpleicons.org/openai'],
    ['name' => 'Prompt Orchestration', 'cat' => 'AI & ML', 'icon' => 'https://cdn.simpleicons.org/openai'],
    ['name' => 'Context Engineering', 'cat' => 'AI & ML', 'icon' => 'https://cdn.simpleicons.org/openai'],
    ['name' => 'AI Training', 'cat' => 'AI & ML', 'icon' => 'https://cdn.simpleicons.org/pytorch'],
    ['name' => 'Pinecone', 'cat' => 'Backend', 'icon' => 'assets/img/brands/ai.svg'],
    ['name' => 'Weaviate', 'cat' => 'Backend', 'icon' => 'assets/img/brands/ai.svg'],
    ['name' => 'Arize Phoenix', 'cat' => 'Tools', 'icon' => 'assets/img/brands/ai.svg'],
    ['name' => 'LangSmith', 'cat' => 'Tools', 'icon' => 'assets/img/brands/ai.svg'],
    ['name' => 'Observability & Evals', 'cat' => 'Tools', 'icon' => 'assets/img/brands/ai.svg'],
    ['name' => 'AI + UX Principles', 'cat' => 'Design', 'icon' => 'assets/img/icons/ui-ux.svg'],
    ['name' => 'Human-in-the-Loop UX', 'cat' => 'Design', 'icon' => 'assets/img/icons/uxtesting.svg'],
    ['name' => 'Supabase', 'cat' => 'Backend', 'icon' => 'https://svgl.app/library/supabase.svg'],
    ['name' => 'NVIDIA', 'cat' => 'Backend', 'icon' => 'https://svgl.app/library/nvidia-icon-dark.svg'],
    ['name' => 'Cisco', 'cat' => 'Backend', 'icon' => 'https://svgl.app/library/cisco_dark.svg'],
    ['name' => 'Vercel', 'cat' => 'Deployment', 'icon' => 'https://svgl.app/library/vercel_dark.svg'],
    ['name' => 'PayPal', 'cat' => 'Deployment', 'icon' => 'https://svgl.app/library/paypal.svg'],
    ['name' => 'GitHub', 'cat' => 'Tools', 'icon' => 'https://svgl.app/library/github_dark.svg'],
    ['name' => 'Hugging Face', 'cat' => 'AI & ML', 'icon' => 'https://svgl.app/library/hugging_face.svg'],
    ['name' => 'Discord', 'cat' => 'Tools', 'icon' => 'https://svgl.app/library/discord.svg'],
    ['name' => 'Google Drive', 'cat' => 'Tools', 'icon' => 'https://svgl.app/library/drive.svg'],
    ['name' => 'Proton Mail', 'cat' => 'Tools', 'icon' => 'https://svgl.app/library/protonmail.svg'],
    ['name' => 'Mattermost', 'cat' => 'Tools', 'icon' => 'https://svgl.app/library/mattermost-dark.svg'],
    ['name' => 'Inngest', 'cat' => 'Tools', 'icon' => 'https://svgl.app/library/inngest-dark.svg'],
    ['name' => 'Windsurf', 'cat' => 'Tools', 'icon' => 'https://svgl.app/library/windsurf-dark.svg'],
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
