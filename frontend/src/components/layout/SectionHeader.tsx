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
        flex items-center gap-4 p-6 
        border-4 border-black shadow-[10px_10px_0px_0px_#000000] w-max
        uppercase text-5xl font-display tracking-tight
        ${variantStyles[variant]}
        ${className}
      `}
    >
      {Icon && <Icon size={48} strokeWidth={2.5} />}
      <span>{title}</span>
    </div>
  );
};
