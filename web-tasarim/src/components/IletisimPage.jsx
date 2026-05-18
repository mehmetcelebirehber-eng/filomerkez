import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Phone, MapPin, Send, Clock, CheckCircle2 } from 'lucide-react';

export default function IletisimPage() {
  const [isSubmitted, setIsSubmitted] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    setIsSubmitted(true);
    setTimeout(() => setIsSubmitted(false), 5000);
  };

  return (
    <motion.div 
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
      transition={{ duration: 0.5 }}
      style={{ 
        display: 'flex', 
        flexWrap: 'wrap', 
        minHeight: '100vh', 
        width: '100%', 
        paddingTop: 0
      }}
    >
      {/* Left Panel - Blue Full Height */}
      <div style={{
        flex: '1 1 50%',
        minWidth: '300px',
        background: 'linear-gradient(135deg, #0a192f 0%, #10549c 100%)',
        color: 'white',
        padding: '160px 10% 80px',
        position: 'relative',
        overflow: 'hidden',
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center'
      }}>
        {/* Decorative BG */}
        <div style={{ position: 'absolute', top: '-50px', right: '-50px', width: '300px', height: '300px', background: 'rgba(255,255,255,0.05)', borderRadius: '50%' }}></div>
        <div style={{ position: 'absolute', bottom: '-50px', left: '-50px', width: '300px', height: '300px', background: 'rgba(226,27,27,0.15)', borderRadius: '50%' }}></div>
        
        <div style={{ position: 'relative', zIndex: 10, maxWidth: '600px', margin: '0 auto', width: '100%' }}>
          <h2 style={{ fontSize: '3rem', fontWeight: 800, marginBottom: '20px', fontFamily: 'var(--font-heading)' }}>İletişime Geçin</h2>
          <p style={{ fontSize: '1.2rem', color: 'rgba(255,255,255,0.8)', marginBottom: '60px', lineHeight: 1.6 }}>
            Size en uygun fiyat teklifini sunabilmemiz ve sorularınızı yanıtlayabilmemiz için bizimle iletişime geçin.
          </p>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '40px' }}>
            <div style={{ display: 'flex', gap: '24px', alignItems: 'flex-start' }}>
              <div style={{ background: 'rgba(255,255,255,0.1)', padding: '16px', borderRadius: '50%', color: 'var(--color-accent-secondary)' }}>
                <Phone size={28} />
              </div>
              <div>
                <h4 style={{ fontSize: '1.1rem', marginBottom: '8px', fontWeight: 600, color: 'rgba(255,255,255,0.7)', textTransform: 'uppercase', letterSpacing: '1px' }}>Telefon</h4>
                <p style={{ fontSize: '1.5rem', fontWeight: 700, letterSpacing: '1px' }}>+90 532 473 35 64</p>
              </div>
            </div>

            <div style={{ display: 'flex', gap: '24px', alignItems: 'flex-start' }}>
              <div style={{ background: 'rgba(255,255,255,0.1)', padding: '16px', borderRadius: '50%', color: 'var(--color-accent-secondary)' }}>
                <MapPin size={28} />
              </div>
              <div>
                <h4 style={{ fontSize: '1.1rem', marginBottom: '8px', fontWeight: 600, color: 'rgba(255,255,255,0.7)', textTransform: 'uppercase', letterSpacing: '1px' }}>Adres</h4>
                <p style={{ fontSize: '1.1rem', color: 'rgba(255,255,255,0.9)', lineHeight: 1.6 }}>
                  Fevziçakmak Mahallesi<br/>
                  10591. Sk No:26/A<br/>
                  Karatay - KONYA
                </p>
              </div>
            </div>

            <div style={{ display: 'flex', gap: '24px', alignItems: 'flex-start' }}>
              <div style={{ background: 'rgba(255,255,255,0.1)', padding: '16px', borderRadius: '50%', color: 'var(--color-accent-secondary)' }}>
                <Clock size={28} />
              </div>
              <div>
                <h4 style={{ fontSize: '1.1rem', marginBottom: '8px', fontWeight: 600, color: 'rgba(255,255,255,0.7)', textTransform: 'uppercase', letterSpacing: '1px' }}>Çalışma Saatleri</h4>
                <p style={{ fontSize: '1.1rem', color: 'rgba(255,255,255,0.9)' }}>7 Gün / 24 Saat Hizmetinizdeyiz</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Right Panel - Form Full Height */}
      <div style={{ 
        flex: '1 1 50%', 
        minWidth: '300px',
        padding: '160px 10% 80px', 
        background: '#f8fafc',
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center'
      }}>
        <div style={{ width: '100%', height: '100%', minHeight: '500px', display: 'flex', flexDirection: 'column' }}>
          <h3 style={{ fontSize: '2.5rem', marginBottom: '20px', color: 'var(--color-text-primary)', fontFamily: 'var(--font-heading)' }}>
            Konumumuz
          </h3>
          
          <div style={{ 
            flex: 1,
            width: '100%', 
            borderRadius: '24px', 
            overflow: 'hidden', 
            boxShadow: '0 20px 40px rgba(0,0,0,0.1)',
            border: '6px solid white',
            position: 'relative'
          }}>
            <iframe 
              src="https://maps.google.com/maps?q=Mehmet%20%C3%87elebi%20Turizm%2C%20Fevzi%C3%A7akmak%20Mah.%2010591.%20Sk%20No%3A26%2FA%2C%20Karatay%20Konya&t=&z=19&ie=UTF8&iwloc=&output=embed" 
              width="100%" 
              height="100%" 
              style={{ border: 0, filter: 'contrast(1.1) saturation(1.1)' }} 
              allowFullScreen="" 
              loading="lazy" 
              referrerPolicy="no-referrer-when-downgrade"
              title="Mehmet Çelebi Turizm Konum"
            ></iframe>
          </div>
        </div>
      </div>
    </motion.div>
  );
}
