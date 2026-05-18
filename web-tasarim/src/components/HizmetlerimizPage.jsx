import React from 'react';
import { motion } from 'framer-motion';
import { Users, GraduationCap, Compass, Star, Key, ArrowLeft, ArrowRight } from 'lucide-react';

const servicesList = [
  {
    id: 'personel',
    title: "Personel Taşımacılığı",
    description: "Şirketinizin dinamiğine güç katan, vaktinde ve güvenilir personel taşıma çözümleri. Lüks araçlarımızla çalışanlarınız güne zinde başlasın.",
    icon: <Users size={40} color="#FFFFFF" />,
    gradient: "linear-gradient(135deg, #10549c 0%, #1a75ff 100%)",
    delay: 0.1
  },
  {
    id: 'ogrenci',
    title: "Öğrenci Taşımacılığı",
    description: "Geleceğimizin teminatı öğrencilerimiz için maksimum güvenlik, GPS takibi ve deneyimli rehber personel eşliğinde konforlu okul servis hizmetleri.",
    icon: <GraduationCap size={40} color="#FFFFFF" />,
    gradient: "linear-gradient(135deg, #e21b1b 0%, #ff4d4d 100%)",
    delay: 0.2
  },
  {
    id: 'turizm',
    title: "Turizm Taşımacılığı",
    description: "Yurt içi ve yurt dışı tur organizasyonları, bayi toplantıları ve özel etkinlikleriniz için dinamik filo yapımızla özel ulaşım deneyimi.",
    icon: <Compass size={40} color="#FFFFFF" />,
    gradient: "linear-gradient(135deg, #0d47a1 0%, #1976d2 100%)",
    delay: 0.3
  },
  {
    id: 'vip',
    title: "VIP Transfer",
    description: "Özel misafirleriniz, protokol taşımacılığı ve havalimanı transferleri için ultra lüks tasarımlı araçlarımızla birinci sınıf VIP konfor.",
    icon: <Star size={40} color="#FFFFFF" />,
    gradient: "linear-gradient(135deg, #b71c1c 0%, #e53935 100%)",
    delay: 0.4
  },
  {
    id: 'kiralama',
    title: "Araç Kiralama",
    description: "Bireysel ve kurumsal ihtiyaçlarınıza özel, bakımlı ve güvenilir geniş araç filomuzla uzun ve kısa dönem avantajlı kiralama çözümleri.",
    icon: <Key size={40} color="#FFFFFF" />,
    gradient: "linear-gradient(135deg, #1565c0 0%, #42a5f5 100%)",
    delay: 0.5
  }
];

export default function HizmetlerimizPage({ onBack, onServiceSelect }) {
  return (
    <div style={{ background: '#f8fafc', minHeight: '100vh', paddingBottom: '80px', position: 'relative', overflow: 'hidden' }}>
      
      {/* Decorative Background Glows */}
      <div style={{ position: 'absolute', top: '-10%', left: '-10%', width: '500px', height: '500px', background: 'radial-gradient(circle, rgba(16,84,156,0.08) 0%, transparent 70%)', filter: 'blur(60px)', zIndex: 0 }}></div>
      <div style={{ position: 'absolute', top: '40%', right: '-10%', width: '600px', height: '600px', background: 'radial-gradient(circle, rgba(226,27,27,0.05) 0%, transparent 70%)', filter: 'blur(80px)', zIndex: 0 }}></div>

      {/* Premium Hero Header */}
      <div style={{ 
        background: 'linear-gradient(135deg, #0a192f 0%, #10549c 100%)',
        padding: '120px 5% 60px',
        position: 'relative',
        zIndex: 10,
        boxShadow: '0 20px 40px rgba(0,0,0,0.1)'
      }}>
        {/* Back Button */}
        <motion.button 
          onClick={onBack}
          whileHover={{ scale: 1.05, x: -5 }}
          whileTap={{ scale: 0.95 }}
          style={{ 
            position: 'absolute', 
            top: '30px', 
            left: '5%', 
            background: 'rgba(255,255,255,0.1)', 
            backdropFilter: 'blur(10px)',
            border: '1px solid rgba(255,255,255,0.2)', 
            color: 'white', 
            padding: '10px 20px', 
            borderRadius: '50px', 
            cursor: 'pointer', 
            display: 'flex', 
            alignItems: 'center', 
            gap: '8px',
            fontWeight: 600,
            zIndex: 20
          }}
        >
          <ArrowLeft size={18} /> Geri Dön
        </motion.button>

        <div style={{ maxWidth: '1200px', margin: '0 auto', textAlign: 'center' }}>
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
          >
            <h1 style={{ 
              fontSize: '3.5rem', 
              fontWeight: 900, 
              color: 'white', 
              marginBottom: '20px', 
              fontFamily: 'var(--font-heading)',
              letterSpacing: '-1px'
            }}>
              Tüm <span style={{ color: '#e21b1b' }}>Hizmetlerimiz</span>
            </h1>
            <p style={{ color: 'rgba(255,255,255,0.8)', fontSize: '1.2rem', maxWidth: '700px', margin: '0 auto', lineHeight: 1.6 }}>
              Her yolculuğun amacına özel tasarlanmış, teknolojiyle entegre modern ulaşım çözümlerimizle tanışın.
            </p>
          </motion.div>
        </div>
      </div>

      {/* Services List */}
      <div style={{ maxWidth: '1200px', margin: '-40px auto 0', padding: '0 5%', position: 'relative', zIndex: 15 }}>
        <div style={{ display: 'grid', gap: '30px', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))' }}>
          {servicesList.map((service, index) => (
            <motion.div
              key={service.id}
              onClick={() => onServiceSelect(service.id)}
              initial={{ opacity: 0, y: 50 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: service.delay }}
              whileHover={{ 
                y: -10,
                boxShadow: '0 25px 50px rgba(0,0,0,0.1)'
              }}
              style={{
                background: 'white',
                borderRadius: '24px',
                padding: '40px 30px',
                cursor: 'pointer',
                boxShadow: '0 10px 30px rgba(0,0,0,0.05)',
                border: '1px solid rgba(0,0,0,0.03)',
                position: 'relative',
                overflow: 'hidden',
                display: 'flex',
                flexDirection: 'column',
                height: '100%'
              }}
            >
              {/* Premium Icon Box */}
              <div style={{
                width: '80px',
                height: '80px',
                borderRadius: '20px',
                background: service.gradient,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                marginBottom: '25px',
                boxShadow: `0 10px 20px ${service.id === 'ogrenci' || service.id === 'vip' ? 'rgba(226,27,27,0.2)' : 'rgba(16,84,156,0.2)'}`,
                position: 'relative'
              }}>
                <motion.div
                  whileHover={{ rotate: [0, -10, 10, -5, 5, 0] }}
                  transition={{ duration: 0.5 }}
                >
                  {service.icon}
                </motion.div>
              </div>

              <h2 style={{ fontSize: '1.6rem', fontWeight: 800, color: 'var(--color-heading)', marginBottom: '15px', fontFamily: 'var(--font-heading)' }}>
                {service.title}
              </h2>
              
              <p style={{ color: 'var(--color-text-secondary)', fontSize: '1rem', lineHeight: 1.6, flex: 1, marginBottom: '30px' }}>
                {service.description}
              </p>

              <div style={{ display: 'flex', alignItems: 'center', gap: '8px', color: service.id === 'ogrenci' || service.id === 'vip' ? '#e21b1b' : '#10549c', fontWeight: 700, fontSize: '1.05rem', marginTop: 'auto' }}>
                <span style={{ borderBottom: '2px solid transparent', transition: 'border-color 0.3s' }} className="hover-underline">
                  Detayları İncele
                </span>
                <motion.div whileHover={{ x: 5 }}>
                  <ArrowRight size={20} />
                </motion.div>
              </div>

              {/* Card Hover Glow */}
              <motion.div 
                className="card-glow"
                style={{
                  position: 'absolute',
                  top: 0,
                  right: 0,
                  width: '150px',
                  height: '150px',
                  background: service.gradient,
                  filter: 'blur(100px)',
                  opacity: 0,
                  zIndex: 0,
                  transition: 'opacity 0.3s ease'
                }}
              />
              <style>{`
                div:hover > .card-glow { opacity: 0.1 !important; }
                div:hover .hover-underline { border-color: currentColor !important; }
              `}</style>
            </motion.div>
          ))}
        </div>
      </div>
    </div>
  );
}
