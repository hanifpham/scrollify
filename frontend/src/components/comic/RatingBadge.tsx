import React from 'react';
import { Tag } from '@/components/ui';
import { Star } from 'lucide-react';

interface RatingBadgeProps {
  rating: number;
  className?: string;
}

export const RatingBadge: React.FC<RatingBadgeProps> = ({ rating, className = '' }) => {
  return (
    <Tag variant="rating" className={`flex items-center gap-1 px-1.5 ${className}`}>
      <Star className="w-3 h-3 fill-current" />
      <span>{rating.toFixed(1)}</span>
    </Tag>
  );
};
