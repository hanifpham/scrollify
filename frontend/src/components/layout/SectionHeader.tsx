import type { LucideIcon } from 'lucide-react';

interface SectionHeaderProps {
  title: string;
  variant: 'amber' | 'purple' | 'red';
  icon?: LucideIcon;
  className?: string;
}

export const SectionHeader: React.FC<SectionHeaderProps> = ({
  title,
  variant,
  icon: Icon,
  className = '',
}) => {
  const variantStyles = {
    amber: 'bg-accent-amber text-on-accent-amber',
    purple: 'bg-primary text-on-primary',
    red: 'bg-secondary text-on-secondary',
  };

  return (
    <div
      className={`
        inline-flex items-center gap-2 px-4 py-2 
        border-border-standard border-border-black shadow-[3px_3px_0px_0px_#000000]
        uppercase font-bold text-headline-md rounded-sm
        ${variantStyles[variant]}
        ${className}
      `}
    >
      {Icon && <Icon size={24} strokeWidth={2.5} />}
      <span>{title}</span>
    </div>
  );
};
