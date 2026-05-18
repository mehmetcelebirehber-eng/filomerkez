import React from 'react';
import { motion } from 'framer-motion';
import { History, Users, ShieldCheck, MapPin } from 'lucide-react';

export default function AboutUs() {
  const currentYear = new Date().getFullYear();
  const yearsInBusiness = currentYear - 1975;

  return (
    <div style={{ paddingTop: '80px', minHeight: '100vh', background: '#f8fafc', paddingBottom: '80px' }}>
      
      {/* Hero Section */}
      <section style={{ 
        background: 'linear-gradient(135deg, #0a192f 0%, #10549c 100%)', 
        padding: '80px 5%', 
        color: '#FFFFFF',
        textAlign: 'center',
        position: 'relative',
        overflow: 'hidden'
      }}>
        <style>{`
          @keyframes textShine {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
          }
          @media (max-width: 768px) {
            .about-hero-content {
              flex-direction: column;
              text-align: center !important;
            }
          }
        `}</style>
        
        <div style={{ position: 'absolute', top: 0, left: 0, width: '100%', height: '100%', background: 'url("https://placehold.co/1920x400/0a192f/0a192f") center/cover', opacity: 0.2 }}></div>
        
        <div className="about-hero-content" style={{ position: 'relative', zIndex: 10, maxWidth: '1200px', margin: '0 auto', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '60px' }}>
          
          <div style={{ flex: '1', minWidth: '300px', textAlign: 'left' }}>
            <motion.div 
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6 }}
              style={{ display: 'inline-block', padding: '8px 24px', background: 'rgba(226,27,27,0.2)', color: '#ff4d4d', borderRadius: '30px', fontWeight: 800, letterSpacing: '2px', marginBottom: '20px' }}
            >
              KÖKLÜ GEÇMİŞ, GÜVENLİ GELECEK
            </motion.div>
            <motion.h1 
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: 0.1 }}
              style={{ fontSize: '3.5rem', fontWeight: 900, marginBottom: '20px', fontFamily: 'var(--font-heading)', lineHeight: 1.2 }}
            >
              Yarım Asırlık <br/>
              <span style={{ 
                background: 'linear-gradient(90deg, #3b82f6 0%, #e21b1b 50%, #3b82f6 100%)',
                backgroundSize: '200% auto',
                WebkitBackgroundClip: 'text',
                WebkitTextFillColor: 'transparent',
                display: 'inline-block',
                animation: 'textShine 3s linear infinite'
              }}>Tecrübe ve Güven</span>
            </motion.h1>
            <motion.p 
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: 0.2 }}
              style={{ fontSize: '1.2rem', color: 'rgba(255,255,255,0.9)', lineHeight: 1.8 }}
            >
              Konya'da taşımacılık sektörünün öncülerinden olan firmamız, 1975 yılından bugüne üç nesildir süregelen bir hizmet aşkıyla yollara güven taşıyor.
            </motion.p>
          </div>

          <motion.div 
            initial={{ opacity: 0, scale: 0.8 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.8, type: 'spring', bounce: 0.5, delay: 0.3 }}
            style={{ 
              width: '220px', 
              height: '220px', 
              borderRadius: '50%', 
              background: 'linear-gradient(135deg, #e21b1b 0%, #a71d2a 100%)',
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              justifyContent: 'center',
              boxShadow: '0 20px 40px rgba(226,27,27,0.4)',
              border: '8px solid rgba(255,255,255,0.1)',
              position: 'relative',
              flexShrink: 0
            }}
          >
            {/* Spinning Dashed Border Effect */}
            <motion.div 
              animate={{ rotate: 360 }}
              transition={{ duration: 15, repeat: Infinity, ease: 'linear' }}
              style={{ position: 'absolute', top: '-20px', left: '-20px', right: '-20px', bottom: '-20px', borderRadius: '50%', border: '2px dashed rgba(255,255,255,0.3)' }}
            />
            {/* Pulsing inner glow */}
            <motion.div 
              animate={{ scale: [1, 1.05, 1], opacity: [0.5, 0.8, 0.5] }}
              transition={{ duration: 2, repeat: Infinity, ease: 'easeInOut' }}
              style={{ position: 'absolute', top: '0', left: '0', width: '100%', height: '100%', borderRadius: '50%', background: 'radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%)' }}
            />
            <span style={{ fontSize: '5rem', fontWeight: 900, lineHeight: 1, position: 'relative', zIndex: 2 }}>{yearsInBusiness}</span>
            <span style={{ fontSize: '1.4rem', fontWeight: 800, letterSpacing: '3px', opacity: 0.9, position: 'relative', zIndex: 2 }}>. YIL</span>
            <span style={{ fontSize: '0.9rem', opacity: 0.8, marginTop: '8px', position: 'relative', zIndex: 2, fontWeight: 600 }}>Gururla Hizmet</span>
          </motion.div>

        </div>
      </section>

      {/* Story Section */}
      <section style={{ maxWidth: '1200px', margin: '-50px auto 0', padding: '0 5%', position: 'relative', zIndex: 20 }}>
        <div style={{ background: '#FFFFFF', borderRadius: '24px', padding: '60px', boxShadow: '0 20px 40px rgba(0,0,0,0.08)', display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '50px' }}>
          
          <motion.div 
            initial={{ opacity: 0, x: -30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
          >
            <h2 style={{ fontSize: '2.5rem', fontWeight: 800, color: 'var(--color-primary-dark)', marginBottom: '30px', fontFamily: 'var(--font-heading)' }}>Hikayemiz</h2>
            
            <div style={{ position: 'relative', paddingLeft: '30px', borderLeft: '3px solid #e21b1b' }}>
              <div style={{ marginBottom: '40px', position: 'relative' }}>
                <div style={{ position: 'absolute', left: '-38px', top: '0', width: '15px', height: '15px', borderRadius: '50%', background: '#e21b1b', border: '3px solid #FFF' }}></div>
                <h4 style={{ fontSize: '1.3rem', fontWeight: 800, color: '#10549c', marginBottom: '10px' }}>1975 - Temellerin Atılışı</h4>
                <p style={{ color: '#555', lineHeight: 1.7, fontSize: '1.05rem' }}>
                  Firmamız, sektörün duayenlerinden olan merhum kurucumuz <strong>Mehmet Çelebi</strong> tarafından Konya'da kuruldu. O yıllardan itibaren dürüstlük ve güven ilkesiyle temellerimiz atıldı.
                </p>
              </div>

              <div style={{ marginBottom: '40px', position: 'relative' }}>
                <div style={{ position: 'absolute', left: '-38px', top: '0', width: '15px', height: '15px', borderRadius: '50%', background: '#e21b1b', border: '3px solid #FFF' }}></div>
                <h4 style={{ fontSize: '1.3rem', fontWeight: 800, color: '#10549c', marginBottom: '10px' }}>İkinci Kuşak: Bayrak Devri</h4>
                <p style={{ color: '#555', lineHeight: 1.7, fontSize: '1.05rem' }}>
                  Kurucumuzun vefatının ardından, firmamızın yönetimini oğlu <strong>Arif Çelebi</strong> devraldı. Modern vizyonu ve yenilikçi adımlarıyla şirketimizi Konya'nın en saygın filolarından biri haline getirdi.
                </p>
              </div>

              <div style={{ position: 'relative' }}>
                <div style={{ position: 'absolute', left: '-38px', top: '0', width: '15px', height: '15px', borderRadius: '50%', background: '#e21b1b', border: '3px solid #FFF' }}></div>
                <h4 style={{ fontSize: '1.3rem', fontWeight: 800, color: '#10549c', marginBottom: '10px' }}>Üçüncü Kuşak: Geleceğe Hazırlık</h4>
                <p style={{ color: '#555', lineHeight: 1.7, fontSize: '1.05rem' }}>
                  Bugün ise, dedesinin ismini gururla taşıyan <strong>Mehmet Çelebi</strong> (Arif Çelebi'nin oğlu) şirketin mutfağında yetişmekte olup; firmamızın dijitalleşme ve büyüme serüveninde var gücüyle çalışmaktadır. FiloMERKEZ gibi teknolojik yatırımlarımızla geleceğe sağlam adımlarla ilerliyoruz.
                </p>
              </div>
            </div>
          </motion.div>

          <motion.div 
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            style={{ display: 'flex', flexDirection: 'column', gap: '20px', justifyContent: 'center' }}
          >
            <div style={{ padding: '30px', background: '#f8fafc', borderRadius: '20px', display: 'flex', alignItems: 'flex-start', gap: '20px', border: '1px solid rgba(0,0,0,0.05)' }}>
              <div style={{ width: '60px', height: '60px', borderRadius: '16px', background: 'rgba(16,84,156,0.1)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <History size={30} color="#10549c" />
              </div>
              <div>
                <h4 style={{ fontSize: '1.2rem', fontWeight: 800, color: '#333', marginBottom: '8px' }}>Yarım Asırlık Deneyim</h4>
                <p style={{ color: '#666', fontSize: '0.95rem', lineHeight: 1.6 }}>1975'ten bugüne, Konya'da binlerce yolcuyu güvenle sevdiklerine, okullarına ve işlerine ulaştırdık.</p>
              </div>
            </div>

            <div style={{ padding: '30px', background: '#f8fafc', borderRadius: '20px', display: 'flex', alignItems: 'flex-start', gap: '20px', border: '1px solid rgba(0,0,0,0.05)' }}>
              <div style={{ width: '60px', height: '60px', borderRadius: '16px', background: 'rgba(226,27,27,0.1)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <Users size={30} color="#e21b1b" />
              </div>
              <div>
                <h4 style={{ fontSize: '1.2rem', fontWeight: 800, color: '#333', marginBottom: '8px' }}>Aile Şirketi Sıcaklığı</h4>
                <p style={{ color: '#666', fontSize: '0.95rem', lineHeight: 1.6 }}>Kurumsal altyapımızın ardında, üç kuşaktır süregelen güçlü bir aile bağının sıcaklığı ve samimiyeti yatar.</p>
              </div>
            </div>

            <div style={{ padding: '30px', background: '#f8fafc', borderRadius: '20px', display: 'flex', alignItems: 'flex-start', gap: '20px', border: '1px solid rgba(0,0,0,0.05)' }}>
              <div style={{ width: '60px', height: '60px', borderRadius: '16px', background: 'rgba(16,84,156,0.1)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                <MapPin size={30} color="#10549c" />
              </div>
              <div>
                <h4 style={{ fontSize: '1.2rem', fontWeight: 800, color: '#333', marginBottom: '8px' }}>Konya'nın Gururu</h4>
                <p style={{ color: '#666', fontSize: '0.95rem', lineHeight: 1.6 }}>Konya merkezli operasyonlarımızla, bölgenin en saygın ve tercih edilen VIP, Personel ve Öğrenci taşıma filosu olmanın gururunu yaşıyoruz.</p>
              </div>
            </div>
          </motion.div>

        </div>
      </section>

    </div>
  );
}
