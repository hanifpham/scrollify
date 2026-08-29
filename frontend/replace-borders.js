import fs from 'fs';

const files = [
  'd:/SCROLLIFY/frontend/src/components/layout/Navbar.tsx',
  'd:/SCROLLIFY/frontend/src/components/layout/Footer.tsx',
  'd:/SCROLLIFY/frontend/src/components/comic/ComicCard.tsx',
  'd:/SCROLLIFY/frontend/src/components/comic/RecommendationSection.tsx',
  'd:/SCROLLIFY/frontend/src/components/ui/Tabs.tsx'
];

for (const file of files) {
  if (!fs.existsSync(file)) continue;
  let content = fs.readFileSync(file, 'utf-8');
  
  content = content.replace(/border-border-thick border-border-black/g, 'border-4 border-black');
  content = content.replace(/border-border-tag border-border-black/g, 'border-2 border-black');
  content = content.replace(/border-b-8 border-border-black/g, 'border-b-8 border-black');
  content = content.replace(/border-t-8 border-border-black/g, 'border-t-8 border-black');
  content = content.replace(/border-2 border-border-black/g, 'border-2 border-black');
  content = content.replace(/border-b-border-thick border-border-black/g, 'border-b-4 border-black');
  
  // Custom shadows to Tailwind classes? The user just asked for borders, but I'll do just borders.
  
  fs.writeFileSync(file, content);
}

console.log("Done");
