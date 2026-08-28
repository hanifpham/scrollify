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
    <nav className="bg-pure-white border-b-border-thick border-border-black sticky top-0 z-50">
      <div className="max-w-360 mx-auto px-4 md:px-8 h-20 flex items-center justify-between">
        {/* Logo */}
        <Link to="/" className="font-display-lg text-2xl font-bold uppercase tracking-tighter">
          Scrollify
        </Link>

        {/* Center Nav Links (Hidden on small screens for now, or just let them shrink) */}
        <div className="hidden md:flex items-center gap-2">
          {navLinks.map((link) => {
            const isActive = location.pathname === link.path;
            return (
              <Link
                key={link.name}
                to={link.path}
                className={`
                  px-6 py-2 font-bold text-label-lg transition-colors duration-150
                  ${isActive 
                    ? 'bg-primary text-on-primary border-border-standard border-border-black rounded-full' 
                    : 'text-on-surface hover:text-primary hover:bg-surface-container rounded-full'
                  }
                `}
              >
                {link.name}
              </Link>
            );
          })}
        </div>

        {/* Right side: Search & Avatar */}
        <div className="flex items-center gap-4">
          {/* Desktop Search */}
          <div className="relative hidden md:block">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant" size={20} />
            <Input 
              type="text" 
              placeholder="Cari Komik" 
              className="pl-10 w-50 lg:w-70" 
            />
          </div>
          
          {/* Mobile Search Icon */}
          <button className="md:hidden w-10 h-10 rounded-full bg-pure-white border-[3px] border-border-black flex items-center justify-center cursor-pointer hover:bg-surface-variant transition-colors">
            <Search size={20} className="text-on-surface" />
          </button>
          
          <div className="w-10 h-10 rounded-full bg-surface-container border-[3px] border-border-black flex items-center justify-center cursor-pointer hover:bg-surface-variant transition-colors">
            <User size={20} className="text-on-surface" />
          </div>
        </div>
      </div>
    </nav>
  );
};
