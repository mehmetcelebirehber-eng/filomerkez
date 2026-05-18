import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, ScrollView, TouchableOpacity, Alert, ActivityIndicator, Platform } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { MaterialCommunityIcons as Icon } from '@expo/vector-icons';
import api from '../api/axios';
import { FormField, DatePickerInput } from '../components';
import { Picker } from '@react-native-picker/picker';
import * as DocumentPicker from 'expo-document-picker';

export default function TenderFormScreen({ route, navigation }) {
    const { tenderId } = route.params || {};
    const [loading, setLoading] = useState(false);
    const [saving, setSaving] = useState(false);
    
    const [formData, setFormData] = useState({
        institution_name: '',
        tender_date: new Date(),
        tender_registration_number: '',
        vehicle_details: '',
        duration_days: '',
        approximate_cost: '',
        our_bid: '',
        winning_company: '',
        winning_amount: '',
        status: 'Değerlendirmede',
        notes: ''
    });

    const [file, setFile] = useState(null);

    useEffect(() => {
        if (tenderId) {
            fetchTender();
        }
    }, [tenderId]);

    const fetchTender = async () => {
        setLoading(true);
        try {
            const r = await api.get(`/v1/tenders/${tenderId}`);
            if (r.data.success) {
                const d = r.data.data;
                setFormData({
                    institution_name: d.institution_name || '',
                    tender_date: d.tender_date ? new Date(d.tender_date) : new Date(),
                    tender_registration_number: d.tender_registration_number || '',
                    vehicle_details: d.vehicle_details || '',
                    duration_days: d.duration_days ? String(d.duration_days) : '',
                    approximate_cost: d.approximate_cost ? String(d.approximate_cost) : '',
                    our_bid: d.our_bid ? String(d.our_bid) : '',
                    winning_company: d.winning_company || '',
                    winning_amount: d.winning_amount ? String(d.winning_amount) : '',
                    status: d.status || 'Değerlendirmede',
                    notes: d.notes || ''
                });
            }
        } catch (e) {
            Alert.alert('Hata', 'İhale bilgileri alınamadı.');
            navigation.goBack();
        } finally {
            setLoading(false);
        }
    };

    const pickDocument = async () => {
        try {
            const result = await DocumentPicker.getDocumentAsync({
                type: 'application/pdf',
                copyToCacheDirectory: true,
            });
            if (!result.canceled && result.assets && result.assets.length > 0) {
                setFile(result.assets[0]);
            }
        } catch (err) {
            console.error(err);
        }
    };

    const handleSave = async () => {
        if (!formData.institution_name || !formData.tender_date || !formData.status) {
            Alert.alert('Eksik Bilgi', 'Kurum Adı, İhale Tarihi ve Durum zorunludur.');
            return;
        }

        setSaving(true);
        try {
            const data = new FormData();
            Object.keys(formData).forEach(key => {
                if (key === 'tender_date') {
                    data.append('tender_date', formData[key].toISOString().split('T')[0]);
                } else if (formData[key]) {
                    data.append(key, formData[key]);
                }
            });

            if (tenderId) {
                data.append('_method', 'PUT');
            }

            if (file) {
                const fName = file.name || file.uri.split('/').pop();
                data.append('document', { uri: file.uri, name: fName, type: 'application/pdf' });
            }

            if (tenderId) {
                await api.post(`/v1/tenders/${tenderId}`, data, { headers: { 'Content-Type': 'multipart/form-data' }});
                Alert.alert('Başarılı', 'İhale güncellendi.');
            } else {
                await api.post('/v1/tenders', data, { headers: { 'Content-Type': 'multipart/form-data' }});
                Alert.alert('Başarılı', 'Yeni ihale eklendi.');
            }
            navigation.goBack();
        } catch (e) {
            Alert.alert('Hata', 'Kaydedilemedi: ' + (e.response?.data?.message || e.message));
        } finally {
            setSaving(false);
        }
    };

    if (loading) {
        return (
            <View style={st.loader}><ActivityIndicator size="large" color="#3B82F6" /></View>
        );
    }

    return (
        <SafeAreaView style={st.container} edges={['top']}>
            <View style={st.header}>
                <TouchableOpacity onPress={() => navigation.goBack()} style={st.backBtn}>
                    <Icon name="chevron-left" size={28} color="#0F172A" />
                </TouchableOpacity>
                <Text style={st.headerTitle}>{tenderId ? 'İhale Düzenle' : 'Yeni İhale Ekle'}</Text>
                <View style={{ width: 44 }} />
            </View>

            <ScrollView contentContainerStyle={st.formContainer} keyboardShouldPersistTaps="handled">
                <Text style={st.sectionTitle}>Genel Bilgiler</Text>
                
                <Text style={st.label}>KURUM / İHALE ADI *</Text>
                <FormField value={formData.institution_name} onChangeText={t => setFormData({...formData, institution_name: t})} placeholder="Örn: DSİ Taşımacılık İhalesi" />

                <Text style={st.label}>İHALE TARİHİ *</Text>
                <DatePickerInput value={formData.tender_date} onChange={d => setFormData({...formData, tender_date: d})} />

                <Text style={st.label}>İHALE KAYIT NO (İKN)</Text>
                <FormField value={formData.tender_registration_number} onChangeText={t => setFormData({...formData, tender_registration_number: t})} placeholder="Örn: 2025/12345" />

                <Text style={st.label}>ARAÇ İHTİYACI</Text>
                <FormField value={formData.vehicle_details} onChangeText={t => setFormData({...formData, vehicle_details: t})} placeholder="Örn: 2 Minibüs, 1 Otobüs" />

                <View style={{ flexDirection: 'row', gap: 10 }}>
                    <View style={{ flex: 1 }}>
                        <Text style={st.label}>SÜRE (GÜN)</Text>
                        <FormField value={formData.duration_days} onChangeText={t => setFormData({...formData, duration_days: t})} placeholder="365" keyboardType="numeric" />
                    </View>
                    <View style={{ flex: 1 }}>
                        <Text style={st.label}>DURUM *</Text>
                        <View style={st.pickerContainer}>
                            <Picker
                                selectedValue={formData.status}
                                onValueChange={(v) => setFormData({...formData, status: v})}
                                style={{ height: 50 }}
                            >
                                <Picker.Item label="Değerlendirmede" value="Değerlendirmede" />
                                <Picker.Item label="Kazanıldı" value="Kazanıldı" />
                                <Picker.Item label="Kaybedildi" value="Kaybedildi" />
                                <Picker.Item label="İptal Edildi" value="İptal" />
                            </Picker>
                        </View>
                    </View>
                </View>

                <Text style={[st.sectionTitle, { marginTop: 10 }]}>Maliyet & Sonuçlar</Text>

                <View style={{ flexDirection: 'row', gap: 10 }}>
                    <View style={{ flex: 1 }}>
                        <Text style={st.label}>YAKLAŞIK MALİYET (₺)</Text>
                        <FormField value={formData.approximate_cost} onChangeText={t => setFormData({...formData, approximate_cost: t})} placeholder="0.00" keyboardType="numeric" />
                    </View>
                    <View style={{ flex: 1 }}>
                        <Text style={[st.label, { color: '#3B82F6' }]}>BİZİM TEKLİFİMİZ (₺)</Text>
                        <FormField value={formData.our_bid} onChangeText={t => setFormData({...formData, our_bid: t})} placeholder="0.00" keyboardType="numeric" style={{ borderColor: '#3B82F6', backgroundColor: '#EFF6FF' }} />
                    </View>
                </View>

                <View style={{ flexDirection: 'row', gap: 10 }}>
                    <View style={{ flex: 1 }}>
                        <Text style={st.label}>KAZANAN FİRMA</Text>
                        <FormField value={formData.winning_company} onChangeText={t => setFormData({...formData, winning_company: t})} placeholder="Firma Adı" />
                    </View>
                    <View style={{ flex: 1 }}>
                        <Text style={[st.label, { color: '#F59E0B' }]}>KAZANAN TUTAR (₺)</Text>
                        <FormField value={formData.winning_amount} onChangeText={t => setFormData({...formData, winning_amount: t})} placeholder="0.00" keyboardType="numeric" style={{ borderColor: '#F59E0B', backgroundColor: '#FFFBEB' }} />
                    </View>
                </View>

                <Text style={[st.sectionTitle, { marginTop: 10 }]}>Evrak & Notlar</Text>

                <Text style={st.label}>İHALE DOKÜMANI (PDF)</Text>
                <TouchableOpacity style={st.fileBtn} onPress={pickDocument}>
                    {file ? (
                        <>
                            <Icon name="file-check" size={24} color="#10B981" />
                            <Text style={[st.fileBtnText, { color: '#0F172A' }]} numberOfLines={1}>{file.name || 'PDF Seçildi'}</Text>
                        </>
                    ) : (
                        <>
                            <Icon name="file-upload" size={24} color="#64748B" />
                            <Text style={st.fileBtnText}>PDF Dosyası Seç</Text>
                        </>
                    )}
                </TouchableOpacity>

                <Text style={st.label}>NOTLAR</Text>
                <FormField 
                    value={formData.notes} 
                    onChangeText={t => setFormData({...formData, notes: t})} 
                    placeholder="Gelecek yıl için notlar..." 
                    multiline 
                    numberOfLines={4} 
                    style={{ height: 100, textAlignVertical: 'top' }} 
                />

                <TouchableOpacity style={[st.saveBtn, saving && { opacity: 0.7 }]} onPress={handleSave} disabled={saving}>
                    {saving ? <ActivityIndicator color="#fff" /> : <Text style={st.saveBtnText}>Kaydet</Text>}
                </TouchableOpacity>

            </ScrollView>
        </SafeAreaView>
    );
}

const st = StyleSheet.create({
    container: { flex: 1, backgroundColor: '#fff' },
    loader: { flex: 1, justifyContent: 'center', alignItems: 'center' },
    header: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', paddingHorizontal: 16, paddingVertical: 12, borderBottomWidth: 1, borderBottomColor: '#F1F5F9' },
    backBtn: { width: 44, height: 44, borderRadius: 22, backgroundColor: '#F8FAFC', alignItems: 'center', justifyContent: 'center' },
    headerTitle: { fontSize: 18, fontWeight: '800', color: '#0F172A' },
    
    formContainer: { padding: 20, paddingBottom: 60 },
    sectionTitle: { fontSize: 16, fontWeight: '900', color: '#1E293B', marginBottom: 16 },
    label: { fontSize: 11, fontWeight: '800', color: '#64748B', marginBottom: 8, letterSpacing: 0.5 },
    
    pickerContainer: { borderWidth: 1, borderColor: '#E2E8F0', borderRadius: 16, backgroundColor: '#F8FAFC', overflow: 'hidden', marginBottom: 16 },
    
    fileBtn: { flexDirection: 'row', alignItems: 'center', padding: 16, backgroundColor: '#F8FAFC', borderRadius: 16, borderWidth: 1, borderColor: '#E2E8F0', borderStyle: 'dashed', gap: 10, marginBottom: 16 },
    fileBtnText: { fontSize: 14, fontWeight: '600', color: '#64748B', flex: 1 },
    
    saveBtn: { backgroundColor: '#0F172A', borderRadius: 16, paddingVertical: 18, alignItems: 'center', marginTop: 20, shadowColor: '#000', shadowOffset: { width: 0, height: 4 }, shadowOpacity: 0.2, shadowRadius: 8, elevation: 4 },
    saveBtnText: { color: '#fff', fontSize: 16, fontWeight: '800' }
});
