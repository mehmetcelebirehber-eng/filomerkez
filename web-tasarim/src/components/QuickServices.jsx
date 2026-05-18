import React from 'react';
import { motion } from 'framer-motion';
import { Users, GraduationCap, Compass, Star, Key } from 'lucide-react';

const services = [
  { title: 'PERSONEL TAŞIMACILIĞI', icon: Users },
  { title: 'ÖĞRENCİ TAŞIMACILIĞI', icon: GraduationCap },
  { title: 'TURİZM TAŞIMACILIĞI', icon: Compass },
  { title: 'VIP TRANSFER', icon: Star },
  { title: 'ARAÇ KİRALAMA', icon: Key },
];

export default function QuickServices({ activeIndex = 0, onServiceClick }) {
  return (
    <div className="quick-services-wrapper" style={{ position: 'relative', zIndex: 20, width: '100%', padding: '0 2%' }}>
      <style>{`
        .quick-services-wrapper {
          margin-top: -55px;
        }
        .service-title {
          font-size: 1.1rem;
          font-weight: 800;
          line-height: 1.3;
          text-align: center;
          margin-top: 25px;
          font-family: var(--font-heading), 'Inter', sans-serif;
          text-transform: uppercase;
          letter-spacing: 0.5px;
        }
        .service-icon-wrapper {
          position: absolute;
          top: -32px;
          left: 50%;
          transform: translateX(-50%);
          width: 64px;
          height: 64px;
          border-radius: 50%;
          border: 4px solid #FFF;
          display: flex;
          align-items: center;
          justify-content: center;
          box-shadow: 0 8px 20px rgba(0,0,0,0.25);
          transition: all 0.3s ease;
        }
        .service-item {
          flex: 1;
          height: 110px;
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          position: relative;
          cursor: pointer;
          transition: all 0.3s ease;
        }
        .service-item:hover .service-icon-wrapper {
          transform: translateX(-50%) translateY(-5px);
        }
        
        /* Desktop Rounding */
        .service-item:first-child { border-top-left-radius: 55px; border-bottom-left-radius: 55px; }
        .service-item:last-child { border-top-right-radius: 55px; border-bottom-right-radius: 55px; }
        
        @media (max-width: 768px) {
          .quick-services-wrapper { margin-top: -22px !important; }
          .services-bar { border-radius: 12px !important; box-shadow: 0 6px 12px rgba(0,0,0,0.08) !important; }
          .service-item { height: 45px !important; }
          .service-item:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
          .service-item:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
          .service-icon-wrapper { width: 26px !important; height: 26px !important; top: -13px !important; border-width: 2px !important; box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important; }
          .service-icon-wrapper svg { width: 14px !important; height: 14px !important; }
          .service-title { font-size: 0.42rem !important; margin-top: 8px !important; letter-spacing: -0.3px !important; font-weight: 800 !important; line-height: 1.1 !important; }
        }
      `}</style>
      
      <motion.div 
        className="services-bar"
        initial={{ opacity: 0, y: 50 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.8, delay: 0.2 }}
        style={{ 
          maxWidth: '1200px', 
          margin: '0 auto', 
          display: 'flex',
          background: '#FFF',
          borderRadius: '55px',
          boxShadow: '0 20px 40px rgba(0,0,0,0.15)',
          position: 'relative'
        }}
      >
        {services.map((service, index) => {
          const isActive = index === activeIndex;
          
          let bg = '#FFFFFF';
          let textColor = '#0a3d75'; // Premium Dark Blue
          let iconBg = 'linear-gradient(145deg, #146dc7, #0a3d75)'; // Logo Blue 3D gradient
          let borderRight = index < services.length - 1 ? '1px solid rgba(0,0,0,0.06)' : 'none';
          
          // If active, give it the premium red highlight
          if (isActive) {
            bg = 'linear-gradient(135deg, #e63946 0%, #a71d2a 100%)';
            textColor = '#FFFFFF';
            iconBg = '#e63946';
            borderRight = 'none';
          }
          
          return (
            <div 
              key={index}
              onClick={() => onServiceClick && onServiceClick(index)}
              className="service-item"
              style={{ background: bg, borderRight: borderRight }}
            >
              <div className="service-icon-wrapper" style={{ background: iconBg }}>
                <service.icon color="#FFFFFF" size={30} />
              </div>
              <span className="service-title" style={{ color: textColor }}>
                {service.title.split(' ')[0]} <br />
                {service.title.split(' ').slice(1).join(' ')}
              </span>
            </div>
          );
        })}
      </motion.div>
    </div>
  );
}
