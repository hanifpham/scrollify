import { Link } from 'react-router-dom';
import { MessageCircle, Camera, Code, Globe } from 'lucide-react';

export const Footer = () => {
  return (
    <footer className="bg-surface border-t-border-thick border-border-black pt-12 pb-28 md:pb-8 mt-auto">
      <div className="max-w-360 mx-auto px-4 md:px-8">
        <div className="flex flex-col lg:flex-row justify-between gap-12 mb-12">
          {/* Logo & Tagline */}
          <div className="max-w-xs">
            <Link to="/" className="font-display-lg text-3xl font-bold uppercase tracking-tighter mb-4 block">
              Scrollify
            </Link>
            <p className="text-on-surface-variant font-bold text-body-md">
              High-energy comic reading experience. Read without limits, explore without boundaries.
            </p>
          </div>

          {/* Links Columns */}
          <div className="grid grid-cols-2 md:grid-cols-3 gap-8 w-full lg:w-auto">
            {/* Explore */}
            <div className="flex flex-col gap-3">
              <h4 className="font-bold text-headline-md uppercase text-primary">Explore</h4>
              <Link to="#" className="font-bold text-on-surface hover:text-primary transition-colors">Trending</Link>
              <Link to="#" className="font-bold text-on-surface hover:text-primary transition-colors">New Releases</Link>
              <Link to="#" className="font-bold text-on-surface hover:text-primary transition-colors">Originals</Link>
              <Link to="#" className="font-bold text-on-surface hover:text-primary transition-colors">Genres</Link>
            </div>

            {/* Community */}
            <div className="flex flex-col gap-3">
              <h4 className="font-bold text-headline-md uppercase text-secondary">Community</h4>
              <Link to="#" className="font-bold text-on-surface hover:text-secondary transition-colors">Forum</Link>
              <Link to="#" className="font-bold text-on-surface hover:text-secondary transition-colors">Events</Link>
              <Link to="#" className="font-bold text-on-surface hover:text-secondary transition-colors">Creators</Link>
              <Link to="#" className="font-bold text-on-surface hover:text-secondary transition-colors">Discord</Link>
            </div>

            {/* Support */}
            <div className="flex flex-col gap-3">
              <h4 className="font-bold text-headline-md uppercase text-tertiary">Support</h4>
              <Link to="#" className="font-bold text-on-surface hover:text-tertiary transition-colors">Help Center</Link>
              <Link to="#" className="font-bold text-on-surface hover:text-tertiary transition-colors">About Us</Link>
              <Link to="#" className="font-bold text-on-surface hover:text-tertiary transition-colors">Terms</Link>
              <Link to="#" className="font-bold text-on-surface hover:text-tertiary transition-colors">Privacy</Link>
            </div>
          </div>
        </div>

        {/* Bottom Section */}
        <div className="flex flex-col md:flex-row justify-between items-center gap-6 pt-8 border-t-[3px] border-border-black">
          <p className="font-bold text-on-surface-variant text-label-lg">
            &copy; {new Date().getFullYear()} Scrollify. All rights reserved.
          </p>

          <div className="flex flex-col sm:flex-row items-center gap-6">
            {/* Social Icons */}
            <div className="flex items-center gap-4">
              <a href="#" className="w-10 h-10 rounded-full bg-pure-white border-2 border-border-black flex items-center justify-center hover:bg-primary hover:text-on-primary transition-colors">
                <MessageCircle size={20} />
              </a>
              <a href="#" className="w-10 h-10 rounded-full bg-pure-white border-2 border-border-black flex items-center justify-center hover:bg-secondary hover:text-on-secondary transition-colors">
                <Camera size={20} />
              </a>
              <a href="#" className="w-10 h-10 rounded-full bg-pure-white border-2 border-border-black flex items-center justify-center hover:bg-surface-variant transition-colors">
                <Code size={20} />
              </a>
            </div>

            {/* Language Selector */}
            <div className="flex items-center gap-2 bg-pure-white border-2 border-border-black px-3 py-1.5 rounded-sm">
              <Globe size={18} className="text-on-surface" />
              <select className="bg-transparent font-bold text-on-surface focus:outline-none cursor-pointer">
                <option value="id">Indonesia</option>
                <option value="en">English</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};
