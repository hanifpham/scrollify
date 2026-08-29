import { Link, useLocation } from 'react-router-dom';
import { Search, User } from 'lucide-react';
import { Input } from '@/components/ui/Input';
import { Container } from './Container';

export const Navbar = () => {
  const location = useLocation();

  const navLinks = [
    { name: 'Home', path: '/' },
    { name: 'Explore', path: '/explore' },
    { name: 'Library', path: '/library' },
    { name: 'Schedule', path: '/schedule' },
  ];

  return (
    <nav className="bg-pure-white border-b-8 border-border-black sticky top-0 z-50">
      <Container className="h-20 flex items-center justify-between py-0!">
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
                  px-6 py-2 font-bold text-label-lg transition-colors duration-150 border-border-thick border-border-black shadow-[4px_4px_0px_0px_#000000] uppercase rounded-none
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

        {/* Right side: Search & Avatar */}
        <div className="flex items-center gap-4">
          {/* Desktop Search */}
          <div className="relative hidden lg:block">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant" size={20} />
            <Input 
              type="text" 
              placeholder="Cari Komik" 
              className="pl-10 w-64 shadow-[4px_4px_0px_0px_#000000]" 
            />
          </div>
          
          {/* Mobile Search Icon */}
          <button className="md:hidden w-10 h-10 rounded-full bg-pure-white border-[3px] border-border-black flex items-center justify-center cursor-pointer hover:bg-surface-variant transition-colors">
            <Search size={20} className="text-on-surface" />
          </button>
          
          <div className="w-12 h-12 rounded-none bg-primary border-border-thick border-border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center cursor-pointer hover:-translate-y-1 transition-all">
            <User size={24} className="text-pure-white" />
          </div>
        </div>
      </Container>
    </nav>
  );
};
