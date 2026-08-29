import { Link, useLocation } from 'react-router-dom';
import { Search, User } from 'lucide-react';
import { Input } from '@/components/ui/Input';

export const Navbar = () => {
  const location = useLocation();

  const navLinks = [
    { name: 'Home', path: '/' },
    { name: 'Explore', path: '/explore' },
    { name: 'Library', path: '/library' },
    { name: 'Schedule', path: '/schedule' },
  ];

  return (
    <nav className="sticky top-0 z-50 bg-pure-white border-b-8 border-black px-6 py-4 flex items-center justify-between w-full">
      <div className="flex items-center gap-12 flex-1">
        <Link to="/">
          <img src="/images/logo-scrollify.png" alt="Scrollify Brand Logo" className="h-12 w-auto object-contain" />
        </Link>
        <div className="hidden md:flex gap-4 font-black text-[16px] tracking-tight flex-1 justify-center">
          {navLinks.map((link) => {
            const isActive = location.pathname === link.path;
            return (
              <Link
                key={link.name}
                to={link.path}
                className={`
                  px-6 py-2 border-4 border-black shadow-[4px_4px_0px_0px_#000000] uppercase transition-colors
                  ${isActive 
                    ? 'bg-primary text-on-primary' 
                    : 'bg-pure-white text-on-surface hover:bg-zinc-200'
                  }
                `}
              >
                {link.name}
              </Link>
            );
          })}
        </div>
      </div>
      <div className="flex items-center gap-4">
        {/* Desktop Search */}
        <div className="relative hidden lg:block">
          <Input 
            type="text" 
            placeholder="Cari Komik" 
            className="border-4 border-black px-4 py-2 w-64 shadow-[4px_4px_0px_0px_#000000] focus:ring-0 focus:outline-none font-bold text-on-surface" 
          />
          <div className="absolute right-3 top-1/2 -translate-y-1/2 flex gap-1 items-center bg-zinc-200 px-1.5 py-0.5 border-2 border-black text-[10px] font-black text-on-surface">
            <span>Ctrl</span><span>+</span><span>K</span>
          </div>
        </div>
        
        {/* Mobile Search Icon */}
        <button className="lg:hidden w-12 h-12 bg-pure-white border-4 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center hover:bg-zinc-200 transition-colors">
          <Search size={24} className="text-on-surface" />
        </button>
        
        {/* Avatar */}
        <button className="w-12 h-12 bg-primary border-4 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center hover:bg-primary/80 transition-colors">
          <User size={24} className="text-pure-white" />
        </button>
      </div>
    </nav>
  );
};
