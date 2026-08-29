import React from 'react';
import { Tag } from '@/components/ui';
import { RatingBadge } from './RatingBadge';
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
      onClick={onClick} 
      className="w-full h-full flex flex-col group cursor-pointer bg-pure-white border-border-thick border-border-black shadow-[10px_10px_0px_0px_#000000] hover:shadow-[10px_10px_0px_0px_#000000] hover:translate-x-[-2px] hover:translate-y-[-2px] active:shadow-[6px_6px_0px_0px_#000000] active:translate-x-[2px] active:translate-y-[2px] active:scale-[0.98] transition-all duration-150 rounded-none overflow-hidden"
    >
      <div className="relative w-full aspect-3/4 border-b-border-thick border-b-border-black bg-surface-container-high shrink-0">
        <img 
          src={cover_url} 
          alt={title} 
          className="w-full h-full object-cover"
          loading="lazy"
        />
        
        {/* Top Left Badges */}
        <div className="absolute top-2 left-2 flex flex-col gap-1.5 items-start z-10">
          {is_new && (
            <Tag variant="new">NEW</Tag>
          )}
          {latest_chapter && (
            <div className="bg-pure-white border-border-tag border-border-black text-label-sm font-bold px-1.5 py-0.5 rounded-none shadow-[4px_4px_0px_0px_#000000]">
              {formatRelativeTime(latest_chapter.readable_at)}
            </div>
          )}
        </div>

        {/* Top Right Badge */}
        <div className="absolute top-2 right-2 z-10">
          <div className="bg-pure-white text-on-surface border-border-tag border-border-black text-label-sm font-bold uppercase px-1.5 py-0.5 rounded-none shadow-[4px_4px_0px_0px_#000000]">
            {format}
          </div>
        </div>
      </div>

      <div className="p-3 flex-1 flex flex-col gap-2 bg-pure-white">
        <div className={layout === 'update' ? 'h-12' : ''}>
          <h3 
            className={`text-body-md font-bold leading-tight text-on-surface group-hover:text-primary transition-colors ${
              layout === 'centered' ? 'text-center uppercase line-clamp-1' : 'text-left line-clamp-2'
            }`}
            title={title}
          >
            {title}
          </h3>
        </div>
        
        <div className="mt-auto flex items-center justify-between gap-2 pt-1">
          <RatingBadge rating={rating} />
          <span className="text-label-sm text-on-surface-variant truncate">
            {views_label}
          </span>
        </div>
      </div>
    </div>
  );
};
