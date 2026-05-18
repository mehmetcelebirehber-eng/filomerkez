import React from 'react';
import { motion } from 'framer-motion';
import { ShieldCheck, Zap, ArrowRight } from 'lucide-react';

export default function SoftwareShowcase() {
  return (
    <section style={{ 
      padding: '40px 5% 100px', 
      background: 'linear-gradient(135deg, #0a192f 0%, #072e5a 100%)', 
      position: 'relative',
      overflow: 'hidden'
    }}>
      {/* Decorative Background Elements */}
      <div style={{ position: 'absolute', top: '-10%', right: '-5%', width: '500px', height: '500px', background: 'radial-gradient(circle, rgba(226,27,27,0.15) 0%, transparent 70%)', borderRadius: '50%' }}></div>
      <div style={{ position: 'absolute', bottom: '-20%', left: '-10%', width: '600px', height: '600px', background: 'radial-gradient(circle, rgba(20,109,199,0.15) 0%, transparent 70%)', borderRadius: '50%' }}></div>

      <div style={{ maxWidth: '1400px', margin: '0 auto', position: 'relative', zIndex: 10 }}>
        <style>{`
          .showcase-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
          }
          @media (max-width: 992px) {
            .showcase-grid {
              grid-template-columns: 1fr;
              text-align: center;
            }
            .showcase-features {
              justify-content: center;
            }
            .mockup-container { 
              height: 400px !important; 
              margin-top: 40px;
            }
            .mac-mockup { width: 90% !important; right: 0 !important; top: 0 !important; margin: 0 auto; left: 0; }
            .iphone-mockup { width: 140px !important; bottom: -20px !important; left: 10px !important; }
            .credit-box { text-align: left; }
          }
          @media (max-width: 576px) {
            .mockup-container { height: 280px !important; }
            .iphone-mockup { width: 100px !important; bottom: -10px !important; left: 0 !important; }
            .tech-title { font-size: 1.4rem !important; font-weight: 700 !important; letter-spacing: 1px !important; }
            .tech-subtitle { font-size: 1.8rem !important; line-height: 1.2 !important; }
            .credit-box p { font-size: 0.95rem !important; }
          }
          .tech-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 15px;
            font-family: var(--font-heading);
            text-transform: uppercase;
            letter-spacing: 2px;
          }
          .tech-subtitle {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.1;
            font-family: var(--font-heading);
          }
          @media (max-width: 992px) {
            .tech-title { font-size: 1.8rem; font-weight: 700; }
            .tech-subtitle { font-size: 2.2rem; }
          }
          @keyframes textShine {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
          }
        `}</style>

        {/* Centered Section Header */}
        <div style={{ textAlign: 'center', marginBottom: '60px' }}>
          <motion.h2 
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="tech-title"
          >
            ÖZEL YAZILIM & TEKNOLOJİ
          </motion.h2>
          <div style={{ width: '80px', height: '4px', background: 'linear-gradient(90deg, #3b82f6 0%, #e21b1b 100%)', margin: '0 auto', borderRadius: '2px' }}></div>
        </div>

        <div className="showcase-grid">
          
          {/* Text Content Area */}
          <motion.div
            initial={{ opacity: 0, x: -50 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.8 }}
            style={{ color: '#FFFFFF' }}
          >
            <h3 className="tech-subtitle">
              <span style={{ 
                background: 'linear-gradient(90deg, #3b82f6 0%, #e21b1b 50%, #3b82f6 100%)',
                backgroundSize: '200% auto',
                WebkitBackgroundClip: 'text',
                WebkitTextFillColor: 'transparent',
                display: 'inline-block',
                animation: 'textShine 3s linear infinite'
              }}>FiloMERKEZ</span> ile<br/>Geleceğe Taşınıyoruz.
            </h3>
            <p style={{ fontSize: '1.15rem', color: 'rgba(255,255,255,0.8)', lineHeight: 1.8, marginBottom: '30px' }}>
              Operasyonel gücümüzü dijital dünya ile birleştirdik. Müşterilerimiz ve velilerimiz için anlık takip, şeffaf raporlama ve maksimum güvenlik sağlayan <strong>FiloMERKEZ</strong> yönetim sistemi şirketimizin iş yükünü hafifletmektedir.
            </p>
            <div className="credit-box" style={{ padding: '25px', background: 'rgba(255,255,255,0.05)', borderRadius: '20px', borderLeft: '4px solid #e21b1b', marginBottom: '40px' }}>
              <p style={{ margin: 0, fontSize: '1.05rem', fontStyle: 'italic', color: 'rgba(255,255,255,0.9)', lineHeight: 1.6 }}>
                "Bu benzersiz web paneli ve mobil uygulama ekosistemi, Mehmet Çelebi Turizm'e özel olarak <strong style={{ color: '#FFF' }}>Sabri DOĞRU</strong> tarafından tasarlanmış ve geliştirilmiştir."
              </p>
            </div>
            
            <div className="showcase-features" style={{ display: 'flex', gap: '40px', flexWrap: 'wrap', marginBottom: '40px' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '15px' }}>
                <div style={{ width: '50px', height: '50px', borderRadius: '12px', background: 'rgba(20,109,199,0.2)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                  <ShieldCheck size={24} color="#1da1f2" />
                </div>
                <div style={{ fontWeight: 700, fontSize: '1rem', textAlign: 'left' }}>Maksimum<br/>Güvenlik</div>
              </div>
              <div style={{ display: 'flex', alignItems: 'center', gap: '15px' }}>
                <div style={{ width: '50px', height: '50px', borderRadius: '12px', background: 'rgba(226,27,27,0.2)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                  <Zap size={24} color="#e63946" />
                </div>
                <div style={{ fontWeight: 700, fontSize: '1rem', textAlign: 'left' }}>Anlık<br/>Takip</div>
              </div>
            </div>

            {/* Premium Login Button */}
            <motion.a 
              href="https://mehmetcelebiturizm.com/app/login"
              target="_blank"
              rel="noopener noreferrer"
              whileHover={{ scale: 1.05, boxShadow: '0 15px 35px rgba(226,27,27,0.6)' }}
              whileTap={{ scale: 0.95 }}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '12px',
                padding: '16px 36px',
                background: 'linear-gradient(90deg, #e21b1b 0%, #a71d2a 100%)',
                color: '#FFFFFF',
                borderRadius: '50px',
                fontSize: '1.1rem',
                fontWeight: 800,
                textDecoration: 'none',
                boxShadow: '0 10px 25px rgba(226,27,27,0.4)',
                fontFamily: 'var(--font-heading)',
                transition: 'box-shadow 0.3s ease'
              }}
            >
              Yönetim Paneline Giriş <ArrowRight size={20} />
            </motion.a>
          </motion.div>

          {/* Mockups Area */}
          <motion.div
            initial={{ opacity: 0, x: 50 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.8, delay: 0.2 }}
            style={{ position: 'relative', height: '500px', width: '100%' }}
            className="mockup-container"
          >
            {/* PC / Mac Mockup */}
            <motion.div 
              className="mac-mockup"
              animate={{ y: [-5, 5, -5] }}
              transition={{ duration: 6, repeat: Infinity, ease: "easeInOut" }}
              style={{
                position: 'absolute',
                top: '20px',
                right: '0',
                width: '85%',
                background: '#1a1a1a',
                borderRadius: '16px',
                padding: '12px 12px 20px',
                boxShadow: '0 30px 60px rgba(0,0,0,0.5)',
                border: '1px solid rgba(255,255,255,0.1)'
              }}
            >
              {/* Mac Dots */}
              <div style={{ display: 'flex', gap: '6px', marginBottom: '12px', marginLeft: '5px' }}>
                <div style={{ width: '12px', height: '12px', borderRadius: '50%', background: '#ff5f56' }}></div>
                <div style={{ width: '12px', height: '12px', borderRadius: '50%', background: '#ffbd2e' }}></div>
                <div style={{ width: '12px', height: '12px', borderRadius: '50%', background: '#27c93f' }}></div>
              </div>
              <div style={{ width: '100%', aspectRatio: '16/9', background: '#FFFFFF', borderRadius: '8px', overflow: 'hidden', position: 'relative' }}>
                <img src="/images/web-panel-mockup.png" alt="FiloMERKEZ Web Panel" style={{ width: '100%', height: '100%', objectFit: 'cover' }} 
                     onError={(e) => { e.target.src = 'https://placehold.co/800x450/f8fafc/10549c?text=FiloMERKEZ+Web+Panel'; }} />
              </div>
            </motion.div>

            {/* iPhone Mockup */}
            <motion.div 
              className="iphone-mockup"
              animate={{ y: [5, -5, 5] }}
              transition={{ duration: 5, repeat: Infinity, ease: "easeInOut", delay: 1 }}
              style={{
                position: 'absolute',
                bottom: '20px',
                left: '20px',
                width: '180px',
                background: '#111',
                borderRadius: '35px',
                padding: '10px',
                boxShadow: '-20px 30px 50px rgba(0,0,0,0.6)',
                border: '2px solid rgba(255,255,255,0.2)',
                zIndex: 20
              }}
            >
              {/* iPhone Notch */}
              <div style={{ position: 'absolute', top: '10px', left: '50%', transform: 'translateX(-50%)', width: '50%', height: '20px', background: '#111', borderBottomLeftRadius: '15px', borderBottomRightRadius: '15px', zIndex: 30 }}></div>
              <div style={{ width: '100%', aspectRatio: '9/19.5', background: '#FFFFFF', borderRadius: '25px', overflow: 'hidden', position: 'relative' }}>
                 <img src="/images/mobile-app-mockup.png" alt="FiloMERKEZ Mobil Uygulama" style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                      onError={(e) => { e.target.src = 'https://placehold.co/300x650/f8fafc/e21b1b?text=Mobil+App'; }} />
              </div>
            </motion.div>
          </motion.div>

        </div>
      </div>
    </section>
  );
}
