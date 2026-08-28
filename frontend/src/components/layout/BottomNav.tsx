import { Link, useLocation } from 'react-router-dom';
import { Home, Compass, BookMarked, Calendar } from 'lucide-react';

export const BottomNav = () => {
  const location = useLocation();

  const navLinks = [
    { name: 'Home', path: '/', icon: Home },
    { name: 'Explore', path: '/explore', icon: Compass },
    { name: 'Library', path: '/library', icon: BookMarked },
    { name: 'Schedule', path: '/schedule', icon: Calendar },
  ];

  return (
    <nav className="md:hidden fixed bottom-0 w-full z-50 bg-pure-white border-t-border-thick border-border-black h-20 px-2 flex items-center justify-around">
      {navLinks.map((link) => {
        const isActive = location.pathname === link.path;
        const Icon = link.icon;
        
        return (
          <Link
            key={link.name}
            to={link.path}
            className={`
              flex flex-col items-center justify-center w-16 h-16 rounded-md transition-colors
              ${isActive ? 'text-primary' : 'text-on-surface hover:text-primary hover:bg-surface-container'}
            `}
          >
            <Icon size={24} strokeWidth={isActive ? 3 : 2} className="mb-1" />
            <span className={`text-[10px] font-bold ${isActive ? 'text-primary' : 'text-on-surface-variant'}`}>
              {link.name}
            </span>
          </Link>
        );
      })}
    </nav>
  );
};
