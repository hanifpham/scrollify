import { Link } from 'react-router-dom';
import { MessageCircle, Camera, Code } from 'lucide-react';

export const Footer = () => {
  return (
    <footer className="mt-20 border-t-8 border-black bg-surface pt-16 pb-16 px-6">
      <div className="max-w-360 mx-auto">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 pb-16">
          {/* Brand Column */}
          <div className="lg:col-span-2 space-y-6">
            <div className="w-max bg-pure-white border-4 border-black p-4 shadow-[10px_10px_0px_0px_#000000] -rotate-1">
              <Link to="/">
                <img src="/images/logo-scrollify.png" alt="Scrollify Logo" className="h-12 w-auto object-contain" />
              </Link>
            </div>
            <p className="font-black text-2xl leading-tight text-on-surface max-w-md">
              Bukan sekadar bacaan. <span className="text-primary">Scrollify</span> adalah gerbang menuju ribuan dunia fantasi dengan kualitas visual tanpa kompromi.
            </p>
            <div className="flex gap-4">
              <a href="#" className="w-12 h-12 bg-primary text-on-primary border-4 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center hover:-translate-y-1 transition-transform">
                <MessageCircle size={24} />
              </a>
              <a href="#" className="w-12 h-12 bg-accent-amber text-on-accent-amber border-4 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center hover:-translate-y-1 transition-transform">
                <Camera size={24} />
              </a>
              <a href="#" className="w-12 h-12 bg-secondary text-on-secondary border-4 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center hover:-translate-y-1 transition-transform">
                <Code size={24} />
              </a>
            </div>
          </div>

          {/* Explore Column */}
          <div className="space-y-6">
            <h4 className="font-display text-2xl uppercase tracking-tight bg-primary text-on-primary border-4 border-black px-4 py-2 w-max shadow-[4px_4px_0px_0px_#000000]">Explore</h4>
            <ul className="space-y-3 font-bold text-lg">
              <li><Link to="#" className="text-on-surface hover:text-primary hover:underline decoration-4 transition-colors">Trending</Link></li>
              <li><Link to="#" className="text-on-surface hover:text-primary hover:underline decoration-4 transition-colors">New Releases</Link></li>
              <li><Link to="#" className="text-on-surface hover:text-primary hover:underline decoration-4 transition-colors">Originals</Link></li>
              <li><Link to="#" className="text-on-surface hover:text-primary hover:underline decoration-4 transition-colors">Genres</Link></li>
            </ul>
          </div>

          {/* Community Column */}
          <div className="space-y-6">
            <h4 className="font-display text-2xl uppercase tracking-tight bg-accent-amber text-on-accent-amber border-4 border-black px-4 py-2 w-max shadow-[4px_4px_0px_0px_#000000]">Community</h4>
            <ul className="space-y-3 font-bold text-lg">
              <li><Link to="#" className="text-on-surface hover:text-accent-amber hover:underline decoration-4 transition-colors">Forum</Link></li>
              <li><Link to="#" className="text-on-surface hover:text-accent-amber hover:underline decoration-4 transition-colors">Events</Link></li>
              <li><Link to="#" className="text-on-surface hover:text-accent-amber hover:underline decoration-4 transition-colors">Creators</Link></li>
              <li><Link to="#" className="text-on-surface hover:text-accent-amber hover:underline decoration-4 transition-colors">Discord</Link></li>
            </ul>
          </div>

          {/* Support Column */}
          <div className="space-y-6">
            <h4 className="font-display text-2xl uppercase tracking-tight bg-inverse-surface text-inverse-on-surface border-4 border-black px-4 py-2 w-max shadow-[4px_4px_0px_0px_#000000]">Support</h4>
            <ul className="space-y-3 font-bold text-lg">
              <li><Link to="#" className="text-on-surface hover:underline decoration-4 transition-colors">Help Center</Link></li>
              <li><Link to="#" className="text-on-surface hover:underline decoration-4 transition-colors">About Us</Link></li>
              <li><Link to="#" className="text-on-surface hover:underline decoration-4 transition-colors">Terms</Link></li>
              <li><Link to="#" className="text-on-surface hover:underline decoration-4 transition-colors">Privacy</Link></li>
            </ul>
          </div>
        </div>

        {/* Bottom Section */}
        <div className="pt-8 border-t-8 border-black flex flex-col md:flex-row justify-between items-center gap-6">
          <div className="flex items-center gap-4">
            <p className="font-black text-on-surface uppercase tracking-widest text-lg">
              &copy; {new Date().getFullYear()} Scrollify
            </p>
            <div className="h-2 w-2 bg-on-surface rounded-full"></div>
            <p className="font-bold text-on-surface-variant">Made with passion for readers</p>
          </div>

          <div className="flex gap-4">
            <div className="bg-accent-amber border-4 border-black px-6 py-2 font-black text-sm shadow-[4px_4px_0px_0px_#000000] uppercase rotate-2">
              Indonesia
            </div>
            <div className="bg-pure-white border-4 border-black px-6 py-2 font-black text-sm shadow-[4px_4px_0px_0px_#000000] uppercase -rotate-2">
              English
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};
