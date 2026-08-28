import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { Navbar, Footer, BottomNav } from '@/components/layout';
import Home from '@/pages/Home';
import Explore from '@/pages/Explore';
import Library from '@/pages/Library';
import Schedule from '@/pages/Schedule';
import ComponentsPreview from '@/pages/ComponentsPreview';

function App() {
  return (
    <Router>
      <div className="flex flex-col min-h-screen bg-background">
        <Navbar />
        
        <main className="flex-1 pb-20 md:pb-0">
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/explore" element={<Explore />} />
            <Route path="/library" element={<Library />} />
            <Route path="/schedule" element={<Schedule />} />
            <Route path="/dev/components" element={<ComponentsPreview />} />
          </Routes>
        </main>
        
        <Footer />
        <BottomNav />
      </div>
    </Router>
  );
}

export default App;
