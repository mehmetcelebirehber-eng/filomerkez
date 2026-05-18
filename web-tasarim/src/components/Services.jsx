import React from 'react';
import { motion } from 'framer-motion';
import { Users, GraduationCap, Compass, Star, Key, ArrowRight } from 'lucide-react';

const services = [
  {
    id: 0,
    title: "Personel Taşımacılığı",
    description: "İş gücünüzü konforla taşıyor, şirketinizin dinamiğine güç katıyoruz.",
    icon: <Users size={28} color="#FFFFFF" />,
    delay: 0.1
  },
  {
    id: 1,
    title: "Öğrenci Taşımacılığı",
    description: "Geleceğimizin teminatı öğrencilerimiz için maksimum güvenlik.",
    icon: <GraduationCap size={28} color="#FFFFFF" />,
    delay: 0.2
  },
  {
    id: 2,
    title: "Turizm Taşımacılığı",
    description: "Özel günler ve etkinlikler için dinamik filo yapımızla özel deneyim.",
    icon: <Compass size={28} color="#FFFFFF" />,
    delay: 0.3
  },
  {
    id: 3,
    title: "VIP Transfer",
    description: "Lüks araçlarımızla, havalimanı ve şehir içi birinci sınıf konfor.",
    icon: <Star size={28} color="#FFFFFF" />,
    delay: 0.4
  },
  {
    id: 4,
    title: "Araç Kiralama",
    description: "Bakımlı ve güvenilir araç filomuzla uzun/kısa dönem avantajları.",
    icon: <Key size={28} color="#FFFFFF" />,
    delay: 0.5
  }
];

export default function Services({ onServiceClick }) {
  return (
    <section id="hizmetler" style={{ padding: '80px 2%', background: 'linear-gradient(180deg, #f8fafc 0%, #ffffff 100%)', position: 'relative' }}>
      <style>{`
        .services-grid {
          display: grid;
          gap: 20px;
          justify-content: center;
        }
        @media (min-width: 1024px) {
          .services-grid {
            grid-template-columns: repeat(5, 1fr) !important;
          }
        }
        @media (max-width: 1023px) {
          .services-grid {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) !important;
          }
        }
        
        @keyframes icon-shine {
          0% { left: -100%; }
          20% { left: 200%; }
          100% { left: 200%; }
        }
        .service-icon-box {
          position: relative;
          overflow: hidden;
        }
        .service-icon-box::after {
          content: "";
          position: absolute;
          top: 0;
          left: -100%;
          width: 50%;
          height: 100%;
          background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.6) 50%, rgba(255,255,255,0) 100%);
          transform: skewX(-20deg);
          animation: icon-shine 3s infinite ease-in-out;
        }
      `}</style>
      
      <div style={{ maxWidth: '1400px', margin: '0 auto', position: 'relative', zIndex: 10 }}>
        <div style={{ textAlign: 'center', marginBottom: '60px' }}>
          <motion.h2 
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            style={{ fontSize: '2.5rem', fontWeight: 900, color: 'var(--color-primary-dark)', marginBottom: '15px', fontFamily: 'var(--font-heading)' }}
          >
            HİZMETLERİMİZ
          </motion.h2>
          <motion.p 
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.1 }}
            style={{ color: 'var(--color-text-secondary)', fontSize: '1.1rem', maxWidth: '600px', margin: '0 auto' }}
          >
            Her yolculuğun amacına özel tasarlanmış, teknolojiyle entegre modern ulaşım çözümlerimizle tanışın.
          </motion.p>
        </div>
        
        <div className="services-grid">
          {services.map((service) => (
            <motion.div
              key={service.id}
              onClick={() => onServiceClick && onServiceClick(service.id)}
              style={{ 
                background: '#FFFFFF',
                borderRadius: '24px',
                padding: '30px 25px',
                boxShadow: '0 15px 35px rgba(0,0,0,0.04)',
                border: '1px solid rgba(0,0,0,0.03)',
                cursor: 'pointer',
                position: 'relative',
                overflow: 'hidden',
                display: 'flex',
                flexDirection: 'column',
                height: '100%'
              }}
              initial={{ opacity: 0, y: 40 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: service.delay }}
              whileHover={{ 
                y: -10, 
                boxShadow: '0 25px 45px rgba(16,84,156,0.1)',
                borderColor: 'rgba(16,84,156,0.1)'
              }}
            >
              <style>{`
                .service-card-${service.id}:hover .service-icon-box {
                  transform: scale(1.1) rotate(5deg);
                }
                .service-card-${service.id}:hover .service-arrow {
                  transform: translateX(5px);
                  color: #e21b1b !important;
                }
              `}</style>

              <div className={`service-card-${service.id}`} style={{ display: 'flex', flexDirection: 'column', height: '100%' }}>
                {/* Icon Box with Red-Blue Gradient and Shine */}
                <div 
                  className="service-icon-box"
                  style={{ 
                    width: '60px', 
                    height: '60px', 
                    borderRadius: '16px', 
                    background: 'linear-gradient(90deg, #10549c 0%, #e21b1b 100%)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    marginBottom: '20px',
                    boxShadow: '0 10px 20px rgba(226,27,27,0.2)',
                    transition: 'all 0.3s ease'
                  }}
                >
                  {service.icon}
                </div>
                
                <h3 style={{ fontSize: '1.2rem', fontWeight: 800, color: 'var(--color-heading)', marginBottom: '10px', fontFamily: 'var(--font-heading)' }}>
                  {service.title}
                </h3>
                
                <p style={{ color: 'var(--color-text-secondary)', lineHeight: 1.5, fontSize: '0.9rem', flex: 1, marginBottom: '20px' }}>
                  {service.description}
                </p>
                
                <div 
                  className="service-arrow"
                  style={{ 
                    display: 'flex', 
                    alignItems: 'center', 
                    gap: '8px', 
                    color: 'var(--color-primary)', 
                    fontWeight: 800, 
                    fontSize: '0.9rem',
                    transition: 'all 0.3s ease',
                    marginTop: 'auto'
                  }}
                >
                  Daha Fazla İncele <ArrowRight size={16} />
                </div>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
