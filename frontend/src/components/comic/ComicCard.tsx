import React from 'react';
import { formatRelativeTime } from '@/lib/utils/time';

export interface ComicCardProps {
  id: string;
  title: string;
  cover_url: string;
  format: 'manhwa' | 'manga' | 'manhua' | 'other' | string;
  status: 'ongoing' | 'completed' | 'hiatus' | 'cancelled' | string;
  rating: number;
  views_label: string;
  latest_chapter: {
    id: string;
    number: string;
    readable_at: string;
  } | null;
  is_new: boolean;
  tags: string[];
  layout?: 'centered' | 'update';
  onClick?: () => void;
}

export const ComicCard: React.FC<ComicCardProps> = ({
  title,
  cover_url,
  format,
  rating,
  views_label,
  latest_chapter,
  is_new,
  layout = 'centered',
  onClick,
}) => {
  return (
    <div 
      className="space-y-4 group h-full flex flex-col cursor-pointer"
      onClick={onClick}
    >
      {/* Wrapper for image */}
      <div className="relative aspect-3/4 bg-surface-container-high border-4 border-black shadow-[10px_10px_0px_0px_#000000] group-hover:shadow-[10px_10px_0px_0px_#000000] group-hover:translate-x-[-2px] group-hover:translate-y-[-2px] group-active:shadow-[6px_6px_0px_0px_#000000] group-active:translate-x-[2px] group-active:translate-y-[2px] group-active:scale-[0.98] transition-all duration-150 overflow-hidden">
        <img 
          src={cover_url} 
          alt={title} 
          className="w-full h-full object-cover border-b-4 border-black"
          loading="lazy"
        />
        
        {/* Top Left Badges */}
        <div className="absolute top-2 left-2 flex gap-2 z-10">
          {latest_chapter && (
            <span className="bg-pure-white text-on-surface px-2 py-1 border-2 border-black shadow-[4px_4px_0px_0px_#000000] text-xs font-black">
              {formatRelativeTime(latest_chapter.readable_at)}
            </span>
          )}
          {is_new && (
            <span className="bg-secondary text-on-secondary px-2 py-1 border-2 border-black shadow-[4px_4px_0px_0px_#000000] text-xs font-black uppercase">
              NEW
            </span>
          )}
        </div>

        {/* Top Right Badge */}
        <div className="absolute top-2 right-2 z-10">
          <span className="bg-pure-white text-on-surface px-2 py-1 border-2 border-black shadow-[4px_4px_0px_0px_#000000] text-xs font-black uppercase">
            {format}
          </span>
        </div>
      </div>

      {/* Wrapper for text */}
      <div className="space-y-1 flex-1 flex flex-col justify-between">
        <div className={layout === 'update' ? 'h-12' : ''}>
          <h4 
            className={`font-black text-lg leading-tight text-on-surface group-hover:text-primary transition-colors ${
              layout === 'centered' ? 'text-center uppercase line-clamp-1' : 'text-left line-clamp-2'
            }`}
            title={title}
          >
            {title}
          </h4>
        </div>
        
        <div className={`flex items-center gap-2 mt-1 ${layout === 'centered' ? 'justify-center' : 'justify-start'}`}>
          <span className="bg-accent-amber text-on-accent-amber px-2 py-0.5 border-2 border-black shadow-[4px_4px_0px_0px_#000000] text-xs font-black">
            ★ {rating}
          </span>
          <span className="bg-pure-white text-on-surface px-2 py-0.5 border-2 border-black shadow-[4px_4px_0px_0px_#000000] text-xs font-black">
            {views_label}
          </span>
        </div>
      </div>
    </div>
  );
};
