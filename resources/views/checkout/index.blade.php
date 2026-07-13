<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0a89d9">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="PRP Shop">
    <link rel="apple-touch-icon" href="{{ asset('icon/PRP_192x192.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>فروش کیت پی آر پی</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">


    <style>
        @font-face {
            font-family: 'Vazirmatn';
            src: url('{{ asset('fonts/Vazirmatn-Regular.woff2') }}') format('woff2');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Vazirmatn';
            src: url('{{ asset('fonts/Vazirmatn-Medium.woff2') }}') format('woff2');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Vazirmatn';
            src: url('{{ asset('fonts/Vazirmatn-Bold.woff2') }}') format('woff2');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }

        body {
            font-family: 'Vazirmatn', sans-serif !important;
        }

        .sub-step {
            display: none;
        }

        .vpn-close-btn {
            background: none;
            border: none;
            color: #856404;
            font-size: 16px;
            cursor: pointer;
            padding: 0 4px;
            margin-right: auto;
            flex-shrink: 0;
            line-height: 1;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .vpn-close-btn:hover {
            opacity: 1;
        }

        .vpn-warning {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #fff8e1;
            border: 1px solid #ffc107;
            border-radius: 16px;
            padding: 16px 18px;
            margin-bottom: 16px;
            text-align: right;
            direction: rtl;
        }

        .vpn-warning-icon {
            font-size: 24px;
            flex-shrink: 0;
        }

        .vpn-warning-text {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .vpn-warning-text strong {
            font-size: 15px;
            color: #856404;
        }

        .vpn-warning-text span {
            font-size: 13px;
            color: #856404;
        }

        .sub-step.active-sub {
            display: block;
        }

        .sub-products-title {
            margin: 10px 0 20px 0;
            font-size: 1.1rem;
            color: #334155;
        }

        .sub-product-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 12px;
            background: #f8fafc;
            transition: all 0.2s;
        }

        .sub-product-item:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
        }

        .sub-product-info {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            user-select: none;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #e2e8f0;
            padding: 12px;
            text-align: right;
            font-size: 0.9rem;
        }

        .invoice-table th {
            background-color: #f1f5f9;
            color: #475569;
        }

        .total-row {
            font-weight: bold;
            background-color: #f8fafc;
            color: #0f172a;
            font-size: 1rem;
        }

        .badge-optional {
            font-size: 0.75rem;
            background: #3b82f6;
            padding: 3px 8px;
            border-radius: 6px;
            color: #fff;
            margin-right: 6px;
        }

        .sub-qty-container {
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 0.4;
            pointer-events: none;
            transition: all 0.2s;
        }

        .sub-qty-container.enabled {
            opacity: 1;
            pointer-events: auto;
        }

        /* ================= مکمل‌ها ================= */

        .sub-product-card {
            display: flex;
            gap: 18px;
            padding: 18px;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            margin-bottom: 18px;
            transition: 0.25s;
        }

        .sub-product-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }

        .sub-product-image {
            display: flex;
            justify-content: center;
            flex-shrink: 0;
        }

        .sub-product-image img {
            width: 380px;
            height: 220px;
            border-radius: 16px;
            object-fit: cover;
            display: block;
        }

        .sub-product-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sub-product-top {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 20px;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .sub-product-check {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            cursor: pointer;
        }

        .sub-product-check input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .sub-product-price {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
            white-space: nowrap;
        }

        .sub-product-price span {
            font-size: 0.9rem;
            color: #64748b;
        }

        .sub-product-desc {
            margin: 3px 0;
            color: #78899f;
            line-height: 1.8;
            font-size: 0.92rem;
        }

        .sub-product-bottom {
            display: flex;
            justify-content: flex-end;
        }

        .sub-qty-box {
            display: flex;
            flex-direction: column;
            gap: 8px;
            opacity: 0.4;
            transition: 0.25s;
        }

        .sub-qty-box.enabled {
            opacity: 1;
            pointer-events: auto;
        }

        .sub-qty-box label {
            font-size: 0.85rem;
            color: #475569;
        }

        .sub-qty-input {
            width: 90px;
            height: 42px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 0 12px;
            font-family: inherit;
            font-size: 0.95rem;
            outline: none;
            transition: 0.2s;
        }

        .sub-qty-input:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
        }

        .sub-product-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
        }

        .sub-qty-box {
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        .sub-qty-input {
            pointer-events: auto !important;
            background: #fff !important;
            cursor: text !important;
        }

        /* ================= فاکتور جدید ================= */

        /*.invoice-product-item {*/
        /*    display: flex;*/
        /*    justify-content: space-between;*/
        /*    align-items: center;*/
        /*    gap: 20px;*/
        /*    padding: 14px 40px;*/
        /*    border-bottom: 1px solid #e2e8f0;*/
        /*}*/

        /*.invoice-product-item:last-child {*/
        /*    border-bottom: none;*/
        /*}*/

        .invoice-product-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .invoice-product-name {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
        }

        .invoice-product-price {
            font-size: 0.9rem;
            color: #64748b;
        }

        /*.invoice-qty-controller {*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*    gap: 10px;*/
        /*}*/

        /*.invoice-qty-btn {*/
        /*    width: 34px;*/
        /*    height: 34px;*/
        /*    border: none;*/
        /*    border-radius: 10px;*/
        /*    background: #f1f5f9;*/
        /*    font-size: 1.1rem;*/
        /*    cursor: pointer;*/
        /*    transition: 0.2s;*/
        /*}*/

        /*.invoice-qty-btn:hover {*/
        /*    background: #e2e8f0;*/
        /*}*/

        /*.invoice-qty-input {*/
        /*    width: 60px;*/
        /*    height: 38px;*/
        /*    text-align: center;*/
        /*    border: none;*/
        /*    border-radius: 10px;*/
        /*    font-family: inherit;*/
        /*    font-size: 0.95rem;*/
        /*}*/

        .invoice-line-total {
            min-width: 130px;
            text-align: left;
            font-weight: 700;
            color: #0f172a;
        }

        .invoice-total-box {
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 18px;
            border-top: 2px solid #e2e8f0;
            font-size: 1.1rem;
            font-weight: 800;
        }

        /* ================= TOAST ================= */

        .toast-container {
            position: fixed;
            top: 24px;
            left: 24px;
            background: #0f172a;
            color: white;
            border-radius: 18px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 320px;
            z-index: 999999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-20px);
            transition: opacity .25s ease, transform .25s ease, visibility .25s;
        }

        .toast-container.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .toast-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #22c55e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .toast-message {
            font-size: .88rem;
            color: rgba(255, 255, 255, .8);
        }

        .toast-close {
            border: none;
            background: transparent;
            color: white;
            cursor: pointer;
            font-size: 1.1rem;
        }

        .upload-wrapper {
            width: 100%;
        }

        .upload-box {
            border: 2px dashed #b8def6;
            border-radius: 14px;
            padding: 60px 30px;
            text-align: center;
            cursor: pointer;
            transition: .3s;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .upload-box:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .upload-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }

        .upload-box.dragging {
            border-color: #2563eb;
            background: #dbeafe;
        }

        .upload-content p {
            font-size: 15px;
            font-weight: 600;
            color: #01496D;
            margin: 0;
        }

        .upload-content span {
            font-size: 13px;
            color: #64748b;
        }

        .upload-icon {
            font-size: 40px;
        }

        .upload-icon-img {
            display: block;
            margin: 0 auto;
        }

        .preview-grid {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
        }

        .preview-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: white;
        }

        .preview-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }

        .remove-btn {
            position: absolute;
            top: 6px;
            left: 6px;
            width: 24px;
            height: 24px;
            border: none;
            border-radius: 50%;
            background: red;
            color: white;
            cursor: pointer;
            font-size: 12px;
        }

        .error-message {
            margin-top: 3px !important;
            display: none;
        }

        .preview-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: white;

            opacity: 0;
            transform: translateY(15px) scale(.95);

            animation: previewFade .35s ease forwards;
        }

        @keyframes previewFade {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .preview-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
            transition: .3s;
        }

        .preview-item:hover img {
            transform: scale(1.05);
        }

        .step5-actions {
            margin-top: 40px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .btn-success {
            background: #16a34a;
            color: white;
            padding: 20px 55px;
            font-size: 22px;
            box-shadow: 0 10px 0 #15803d;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-weight: 700;
            transition: .2s;
        }

        .btn-success:hover {
            transform: translateY(4px);
            box-shadow: 0 6px 0 #15803d;
        }

        .desktop-receipt {
            display: flex;
        }

        .mobile-receipt {
            display: none;
        }

        .mobile-receipt-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            direction: rtl;
        }

        .mobile-bank-row {
            justify-content: flex-end;
        }

        .mobile-receipt-label {
            font-size: 14px;
            font-weight: 500;
            color: #30425F;
            white-space: nowrap;
        }

        .mobile-receipt-value {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 16px;
            font-weight: 700;
            color: #01496D;
        }

        .card-row-value {
            direction: ltr;
        }

        .mobile-receipt-divider {
            height: 1px;
            background: #b8def6;
            margin: 4px 0;
        }

        .desktop-receipt {
            display: none;
        }

        .mobile-receipt {
            display: block;
        }

        .step5-actions {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .btn-success {
            font-size: 17px;
            padding: 14px 24px;
        }

        .receipt-info-box {
            border-radius: 24px;
            padding: 20px 24px;
            background: #e1f1fc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .desktop-receipt {
            display: flex;
        }

        .mobile-receipt {
            display: none;
        }

        .card-number-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            direction: rtl;
        }

        .card-number-label {
            font-size: 14px;
            font-weight: 500;
            color: #30425F;
            white-space: nowrap;
        }

        .card-number-value {
            font-size: 16px;
            font-weight: 600;
            color: #01496D;
            direction: ltr;
        }

        .receipt-amount {
            display: flex;
            align-items: center;
            gap: 6px;
            direction: rtl;
        }

        .receipt-amount strong {
            font-size: 20px;
            font-weight: 700;
            color: #01496D;
        }

        .receipt-amount-unit {
            font-size: 14px;
            font-weight: 500;
            color: #01496D;
        }

        .receipt-info-mobile {
            background: #e1f1fc;
            border-radius: 20px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: none;
        }

        .mobile-receipt-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            direction: rtl;
        }

        .mobile-receipt-label {
            font-size: 14px;
            font-weight: 500;
            color: #30425F;
            white-space: nowrap;
        }

        .mobile-receipt-value {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 16px;
            font-weight: 700;
            color: #01496D;
            direction: ltr;
        }

        .mobile-receipt-divider {
            height: 1px;
            background: #b8def6;
        }


        .mob-receipt {
            display: none;
            background: #e1f1fc;
            border-radius: 20px;
            padding: 16px 20px;
            margin-bottom: 20px;
            direction: rtl;
        }

        .mob-row {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        .mob-label {
            font-size: 14px;
            font-weight: 500;
            color: #30425F;
            white-space: nowrap;
        }

        .mob-val {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 6px;
            direction: ltr;
        }

        .mob-val strong {
            font-size: 18px;
            font-weight: 700;
            color: #01496D;
        }

        .mob-unit {
            font-size: 13px;
            font-weight: 500;
            color: #01496D;
        }

        .mob-card-num {
            font-size: 15px;
            font-weight: 600;
            color: #01496D;
            direction: ltr;
        }

        .mob-bank {
            font-size: 13px;
            color: #30425F;
            width: 100%;
            text-align: right;
        }

        .mob-line {
            height: 1px;
            background: #b8def6;
            width: 100%;
        }

        .toast-container {
            position: fixed;
            top: 24px;
            left: 24px;
            border-radius: 14px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            min-width: 280px;
            z-index: 999999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-20px);
            transition: opacity .25s ease, transform .25s ease, visibility .25s;
        }

        .toast-container.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .toast-container.toast-success {
            background: #16a34a;
        }

        .toast-container.toast-error {
            background: #a80000;
        }

        .toast-message {
            font-size: 14px;
            font-weight: 600;
            color: rgba(248, 220, 220, 0.75);
        }

        .toast-close {
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            cursor: pointer;
            font-size: 16px;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.2s;
        }

        .toast-close:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        /* ===== کارت سه‌ستونه تخفیف‌دار ===== */
        .product-card-discount {
            display: flex;
            flex-direction: row-reverse; /* راست به چپ: عکس | ویژگی | قیمت */
            gap: 0;
            align-items: stretch;
            border-radius: 16px;
            overflow: hidden;
            background: transparent;
        }

        /* ستون راست — عکس */
        .pcd-image {
            width: 200px;
            flex-shrink: 0;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .pcd-image img {
            width: 100%;
            height: 160px;
            object-fit: contain;
            border-radius: 10px;
        }

        /* ستون وسط — ویژگی‌ها */
        .pcd-features {
            flex: 1;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
        }

        .pcd-features .product-title {
            margin-bottom: 12px;
        }

        .pcd-features .features {
            flex: 1;
        }

        /* ستون چپ — قیمت‌ها */
        .pcd-price {
            width: 200px;
            flex-shrink: 0;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            direction: rtl;
        }

        .pcd-original-price {
            font-size: 18px;
            color: #989DA5;
            font-weight: 400;
            text-decoration: line-through;
        }

        .original-box-price span {
            font-size: 13px;
            color: #989DA5;

        }

        .pcd-off-price {
            font-size: 25px;
            font-weight: 700;
            color: #01496D;
            line-height: 1.2;
        }

        .pcd-off-price span {
            font-size: 16px;
            font-weight: 400;
            color: #01496D;
        }

        .pcd-savings {
            font-size: 12px;
            color: #359DD2;
            font-weight: 600;
            margin-top: 2px;
        }

        .pcd-timer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 5px;
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 7px 10px;
            font-size: 0.8rem;
            color: #856404;
            margin-top: 8px;
        }

        .pcd-timer-display {
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* input تعداد داخل ستون قیمت */
        .pcd-qty-input {
            margin-top: 12px;
            width: 100%;
        }

        /* دکمه ادامه داخل ستون قیمت */
        .pcd-price .btn {
            margin-top: 10px;
            width: 100%;
            text-align: center;
        }

        .pcd-timer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #FBEAEA;
            border: 1px solid #F1B8B8;
            border-radius: 16px;
            padding: 10px 14px;
            font-size: 13px;
            color: #B15959;
            margin-top: 10px;
            width: 100%;
        }

        .pcd-timer-display {
            font-weight: 700;
            letter-spacing: 1px;
            font-size: 14px;
            color: #B15959;
        }

        .mobile-discount-card {
            display: none;
        }

        .product-card-discount {
            display: flex;
        }

        .features-pcd {

            display: grid;
            gap: 10px;
            margin-bottom: 35px;

        }

        .pcd-price .addon-qty-controller {
            margin-top: 12px;
        }

        /* موبایل — ستون‌ها به هم بچین */
        @media (max-width: 640px) {
            .pcd-original-price {
                font-size: 14px;
                color: #989DA5;
                font-weight: 400;
                text-decoration: line-through;
            }

            .original-box-price span {
                font-size: 12px;
                color: #989DA5;

            }


            .pcd-savings {
                font-size: 10px;
                color: #359DD2;
                line-height: 14px;
                font-weight: 600;
                margin-top: 2px;
            }


            .pcd-off-price {
                font-size: 20px;
                font-weight: 700;
                color: #01496D;
                line-height: 1.2;
            }

            .pcd-off-price span {
                font-size: 12px;
                font-weight: 400;
                color: #01496D;
            }

            .product-card-discount {
                display: none;
            }

            .mobile-discount-card {
                display: block;
                border-radius: 20px;
                overflow: hidden;
                background: #fff;
                border: 1px solid #e2e8f0;
            }

            .mdc-image {
                padding: 20px;
                text-align: center;
            }

            .mdc-image img {
                height: 200px;
                width: auto;
                max-width: 100%;
                object-fit: contain;
                border-radius: 12px;
            }

            .mdc-info {
                padding: 16px;
            }

            .mdc-info .product-title {
                font-size: 20px;
                font-weight: 700;
                text-align: start;
                margin-bottom: 14px;
            }

            .mdc-info .features {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .mdc-info .feature {
                font-size: 13px;
                color: #64748B;
            }

            .mdc-price-box {
                padding: 16px;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .mdc-price-box .pcd-qty-input {
                width: 100%;
                margin-top: 12px;
            }

            .mdc-price-box {
                padding: 16px;
                display: flex;
                flex-direction: column;
            }

            .mdc-price-row {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
            }

            .mdc-price-info {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }

            .mdc-price-box .pcd-qty-input {
                width: 110px;
                flex-shrink: 0;
                margin-top: 0;
            }

            .mdc-price-box .pcd-timer {
                width: 100%;
                margin-top: 12px;
            }
        }

    </style>
    <style>
        @media screen and (max-width: 768px) {
            input, textarea, select {
                font-size: 16px !important;
                transform: scale(0.875);
                transform-origin: right center;
                width: 114.28% !important;
            }
        }
    </style>
</head>

<body>
@php
    function toPersian($n) {
        $persianNums = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishNums = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($englishNums, $persianNums, $n);
    }
@endphp

<div id="pwa-install-banner" class="pwa-install-banner">
    <div class="pwa-install-content">
        <img src="{{ asset('icon/PRP_192x192.png') }}" alt="App" class="pwa-app-icon">
        <div class="pwa-app-info">
            <div class="pwa-app-title">تجهیزات پی آر پی</div>
            <div class="pwa-app-desc">نصب اپلیکیشن برای دسترسی سریع‌تر</div>
        </div>
        <button id="pwa-install-btn" class="pwa-install-btn">نصب</button>
        <button id="pwa-close-btn" class="pwa-close-btn">✕</button>
    </div>
</div>
{{-- ======================================================
     تغییر ۱: اضافه کردن enctype و id به تگ form
     ====================================================== --}}
<form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data" id="main-form">
    @csrf
    <div class="container">
        <div class="toast-container" id="toast">
            <div class="toast-content">
                <div class="toast-message" id="toast-msg"></div>
            </div>
            <button type="button" class="toast-close" onclick="closeToast()">×</button>
        </div>
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    try {
                        buildDayOptions();
                    } catch (e) {
                        console.error('buildDayOptions:', e);
                    }
                    try {
                        buildConfirmDayOptions();
                    } catch (e) {
                        console.error('buildConfirmDayOptions:', e);
                    }
                    try {
                        buildTimeOptions();
                    } catch (e) {
                        console.error('buildTimeOptions:', e);
                    }
                    try {
                        buildConfirmTimeOptions();
                    } catch (e) {
                        console.error('buildConfirmTimeOptions:', e);
                    }

                    document.getElementById('actions-bar').style.display = 'none';
                    for (let i = 1; i <= 5; i++) {
                        const sec = document.getElementById('section-' + i);
                        if (sec) sec.style.display = 'none';
                    }
                    document.getElementById('section-success').style.display = 'block';

                    // استپر همه done
                    for (let i = 1; i <= 5; i++) {
                        const dot = document.getElementById('step-dot-' + i);
                        if (dot) dot.className = 'step done';
                        if (i < 5) {
                            const line = document.getElementById('line-' + i);
                            if (line) {
                                line.className = 'line active';
                                line.style.background = '';
                            }
                        }
                    }
                });
            </script>
        @endif

        <!-- =================== STEPPER =================== -->
        <div class="stepper" id="stepper">
            <div class="step-wrapper" onclick="goToStep(1)">
                <div class="dot-wrap">
                    <div class="dot-ring"></div>
                    <div class="step done" id="step-dot-1">۱</div>
                </div>
                <div class="step-title">انتخاب محصول</div>
            </div>

            <div class="line active" id="line-1"></div>

            <div class="step-wrapper" onclick="goToStep(2)">
                <div class="dot-wrap">
                    <div class="dot-ring"></div>
                    <div class="step active" id="step-dot-2">۲</div>
                </div>
                <div class="step-title">ثبت مشخصات</div>
            </div>

            <div class="line" id="line-2"></div>

            <div class="step-wrapper" onclick="goToStep(3)">
                <div class="dot-wrap">
                    <div class="dot-ring"></div>
                    <div class="step" id="step-dot-3">۳</div>
                </div>
                <div class="step-title">اطلاعات ارسال</div>
            </div>

            <div class="line" id="line-3"></div>

            <div class="step-wrapper" onclick="goToStep(4)">
                <div class="dot-wrap">
                    <div class="dot-ring"></div>
                    <div class="step" id="step-dot-4">۴</div>
                </div>
                <div class="step-title">تایید اطلاعات</div>
            </div>

            <div class="line" id="line-4"></div>

            <div class="step-wrapper" onclick="goToStep(5)">
                <div class="dot-wrap">
                    <div class="dot-ring"></div>
                    <div class="step" id="step-dot-5">۵</div>
                </div>
                <div class="step-title">پرداخت</div>
            </div>
        </div>

        <!-- =================== STEP 1 =================== -->
        <div class="section" id="section-1">
            <!-- زیرمرحله اول: محصول اصلی -->
            <div class="sub-step" id="sub-step-1-1">
                <div class="title-area">
                    <h1 class="main-title">
                        <span class="title-desktop">انتخاب محصول</span>
                        <span class="title-mobile">محصول اصلی</span>
                    </h1>
                </div>
                <div class="card">
                    <div class="product-card" @if($mainProduct->off_price) style="display:none" @endif>
                        <input type="hidden" name="prd_id" value="{{ $mainProduct->id }}">
                        <div class="product-image">
                            <img src="{{ asset('storage/' . $mainProduct->image) }}" alt="{{ $mainProduct->name }}">
                        </div>
                        <div class="product-content">
                            <div class="product-title">{{ $mainProduct->name }}</div>
                            @php
                                $features = array_filter(array_map('trim', explode('•', $mainProduct->description)));
                            @endphp
                            <div class="features">
                                @foreach($features as $feature)
                                    <div class="feature">
                                        <div class="check"><img src="{{ asset("icon/TickCircle-Linear-32px.svg") }}">
                                        </div>
                                        {{ $feature }}
                                    </div>
                                @endforeach
                            </div>
                            <div class="bottom-row">
                                <div class="bottom-row-top">
                                    <input type="number"
                                           inputmode="numeric"
                                           pattern="[0-9]*"
                                           id="qty"
                                           class="qty-input"
                                           placeholder="تعداد را تایپ کنید ..."
                                           oninput="changeMainQty(this.value)">
                                    <div class="price">
                                        {{ toPersian(number_format($mainProduct->price)) }}
                                        <span>تومان</span>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-mobile-next" type="button" onclick="nextStep()">ادامه
                                    ←
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================
                         کارت سه‌ستونه تخفیف‌دار — فقط وقتی off_price داره
                         ============================================ --}}
                    @if($mainProduct->off_price)
                        {{--Mobile--}}
                        <div class="mobile-discount-card">

                            {{-- باکس عکس --}}
                            <div class="mdc-image">
                                <img src="{{ asset('storage/' . $mainProduct->image) }}" alt="{{ $mainProduct->name }}">
                            </div>

                            {{-- باکس اسم + مشخصات --}}
                            <div class="mdc-info">
                                <div class="product-title">{{ $mainProduct->name }}</div>
                                @php
                                    $features = array_filter(array_map('trim', explode('•', $mainProduct->description)));
                                @endphp
                                <div class="features">
                                    @foreach($features as $feature)
                                        <div class="feature">
                                            <div class="check"><img
                                                    src="{{ asset("icon/TickCircle-Linear-32px.svg") }}"></div>
                                            {{ $feature }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- باکس قیمت + تعداد + تایمر --}}
                            <div class="mdc-price-box">

                                <div class="mdc-price-row">
                                    {{-- چپ: تعداد --}}
                                    <input type="number"
                                           inputmode="numeric"
                                           pattern="[0-9]*"
                                           id="qty-mobile"
                                           class="qty-input pcd-qty-input"
                                           placeholder="تعداد را تایپ کنید ..."
                                           oninput="changeMainQty(this.value)">

                                    {{-- راست: قیمت‌ها --}}
                                    <div class="mdc-price-info">
                                        <div class="original-box-price">
                                            <span
                                                class="pcd-original-price">{{ toPersian(number_format($mainProduct->price)) }}</span>
                                            <span>تومان</span>
                                        </div>
                                        <div class="pcd-off-price">
                                            {{ toPersian(number_format($mainProduct->off_price)) }}
                                            <span>تومان</span>
                                        </div>
                                        <div class="pcd-savings">
                                            شما {{ toPersian(number_format($mainProduct->price - $mainProduct->off_price)) }}
                                            تومان کمتر پرداخت می‌کنید!
                                        </div>

                                    </div>

                                </div>

                                @if($mainProduct->discount_ends_at && $mainProduct->discount_ends_at->isFuture())
                                    <div class="pcd-timer" id="discount-timer-mobile">
                                        <span>مانده تا اتمام تخفیف!</span>
                                        <span class="pcd-timer-display" id="timer-display-mobile">--:--:--</span>
                                    </div>
                                @endif
                                <button class="btn btn-primary btn-mobile-next" type="button" onclick="nextStep()">ادامه
                                    ←
                                </button>

                            </div>

                        </div>



                        <div class="product-card product-card-discount">
                            <input type="hidden" name="prd_id" value="{{ $mainProduct->id }}">

                            {{-- ستون چپ: قیمت + تعداد + دکمه ادامه --}}
                            <div class="pcd-price">
                                <div class="original-box-price">
                                    <span
                                        class="pcd-original-price">{{ toPersian(number_format($mainProduct->price)) }}</span>
                                    <span>تومان</span>
                                </div>
                                <div class="pcd-off-price">
                                    {{ toPersian(number_format($mainProduct->off_price)) }}
                                    <span>تومان</span>
                                </div>
                                <div class="pcd-savings">
                                    شما {{ toPersian(number_format($mainProduct->price - $mainProduct->off_price)) }}
                                    تومان کمتر پرداخت می‌کنید!
                                </div>

                                @if($mainProduct->discount_ends_at && $mainProduct->discount_ends_at->isFuture())
                                    <div class="pcd-timer" id="discount-timer">
                                        <span>مانده تا اتمام تخفیف!</span>
                                        <span class="pcd-timer-display" id="timer-display">--:--:--</span>
                                    </div>
                                @endif


                            </div>

                            {{-- ستون وسط: عنوان + ویژگی‌ها --}}
                            <div class="pcd-features">
                                <div class="product-title">{{ $mainProduct->name }}</div>
                                @php
                                    $features = array_filter(array_map('trim', explode('•', $mainProduct->description)));
                                @endphp
                                <div class="features-pcd">
                                    @foreach($features as $feature)
                                        <div class="feature">
                                            <div class="check"><img
                                                    src="{{ asset("icon/TickCircle-Linear-32px.svg") }}"></div>
                                            {{ $feature }}
                                        </div>
                                    @endforeach
                                </div>
                                <input type="number"
                                       inputmode="numeric"
                                       pattern="[0-9]*"
                                       id="qty-mobile"
                                       class="qty-input pcd-qty-input"
                                       placeholder="تعداد را تایپ کنید ..."
                                       oninput="changeMainQty(this.value)">

                            </div>

                            {{-- ستون راست: عکس --}}
                            <div class="pcd-image">
                                <img src="{{ asset('storage/' . $mainProduct->image) }}" alt="{{ $mainProduct->name }}">
                            </div>
                            <button class="btn btn-primary btn-mobile-next" type="button" onclick="nextStep()">ادامه
                                ←
                            </button>

                        </div>
                    @endif
                </div>
            </div>

            @foreach($addOns as $index => $addon)
                <div class="sub-step" id="sub-step-1-{{ $index + 2 }}">
                    <div class="title-area">
                        <h1 class="main-title">انتخاب محصول</h1>
                        <p class="subtitle">آیا به محصول مکمل نیز نیاز دارید؟ در صورت الزام، تعداد را مشخص کنید.</p>
                    </div>
                    <div class="card">

                        {{-- ===== کارت معمولی — فقط بدون تخفیف ===== --}}
                        <div class="product-card" @if($addon->off_price) style="display:none" @endif>
                            <div class="product-image">
                                <img src="{{ asset('storage/' . $addon->image) }}" alt="{{ $addon->name }}">
                            </div>
                            <div class="product-content">
                                <div class="product-title">{{ $addon->name }}</div>
                                @php
                                    $features = array_filter(array_map('trim', explode('•', $addon->description)));
                                @endphp
                                <div class="features">
                                    @foreach($features as $feature)
                                        <div class="feature">
                                            <div class="check"><img
                                                    src="{{ asset("icon/TickCircle-Linear-32px.svg") }}"></div>
                                            {{ $feature }}
                                        </div>
                                    @endforeach
                                </div>
                                <div class="bottom-row">
                                    <div class="bottom-row-top">
                                        <div class="addon-qty-controller">
                                            <button type="button" class="qty-btn"
                                                    onclick="changeSubQty({{ $addon->id }}, 1)">+
                                            </button>
                                            <input type="number" min="1" value="1"
                                                   name="addons[{{ $addon->id }}]"
                                                   id="sub-qty-{{ $addon->id }}"
                                                   class="qty-input addon-qty-input"
                                                   oninput="manualSubQty({{ $addon->id }}, this.value)">
                                            <button type="button" class="qty-btn"
                                                    onclick="changeSubQty({{ $addon->id }}, -1)">−
                                            </button>
                                        </div>
                                        <div class="price">
                                            {{ toPersian(number_format($addon->price)) }}
                                            <span>تومان</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($addon->off_price)

                            {{-- ===== دسکتاپ: سه‌ستونه ===== --}}
                            <div class="product-card product-card-discount">
                                <div class="pcd-price">
                                    <div class="original-box-price">
                                        <span
                                            class="pcd-original-price">{{ toPersian(number_format($addon->price)) }}</span>
                                        <span>تومان</span>
                                    </div>
                                    <div class="pcd-off-price">
                                        {{ toPersian(number_format($addon->off_price)) }}
                                        <span>تومان</span>
                                    </div>
                                    <div class="pcd-savings">
                                        شما {{ toPersian(number_format($addon->price - $addon->off_price)) }} تومان کمتر
                                        پرداخت می‌کنید!
                                    </div>

                                    @if($addon->discount_ends_at && $addon->discount_ends_at->isFuture())
                                        <div class="pcd-timer" id="discount-timer-{{ $addon->id }}">
                                            <span>مانده تا اتمام تخفیف!</span>
                                            <span class="pcd-timer-display"
                                                  id="timer-display-{{ $addon->id }}">--:--:--</span>
                                        </div>
                                    @endif

                                </div>

                                <div class="pcd-features">
                                    <div class="product-title">{{ $addon->name }}</div>
                                    @php
                                        $features = array_filter(array_map('trim', explode('•', $addon->description)));
                                    @endphp
                                    <div class="pcd-features">
                                        @foreach($features as $feature)
                                            <div class="feature">
                                                <div class="check"><img
                                                        src="{{ asset("icon/TickCircle-Linear-32px.svg") }}"></div>
                                                {{ $feature }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="addon-qty-controller">
                                        <button type="button" class="qty-btn"
                                                onclick="changeSubQty({{ $addon->id }}, 1)">+
                                        </button>
                                        <input type="number" min="1" value="1"
                                               id="sub-qty-mobile-{{ $addon->id }}"
                                               class="qty-input addon-qty-input"
                                               oninput="manualSubQty({{ $addon->id }}, this.value)">
                                        <button type="button" class="qty-btn"
                                                onclick="changeSubQty({{ $addon->id }}, -1)">−
                                        </button>
                                    </div>
                                </div>

                                <div class="pcd-image">
                                    <img src="{{ asset('storage/' . $addon->image) }}" alt="{{ $addon->name }}">
                                </div>
                            </div>

                            {{-- ===== موبایل: سه باکس عمودی ===== --}}
                            <div class="mobile-discount-card">

                                <div class="mdc-image">
                                    <img src="{{ asset('storage/' . $addon->image) }}" alt="{{ $addon->name }}">
                                </div>

                                <div class="mdc-info">
                                    <div class="product-title">{{ $addon->name }}</div>
                                    @php
                                        $features = array_filter(array_map('trim', explode('•', $addon->description)));
                                    @endphp
                                    <div class="features">
                                        @foreach($features as $feature)
                                            <div class="feature">
                                                <div class="check"><img
                                                        src="{{ asset("icon/TickCircle-Linear-32px.svg") }}"></div>
                                                {{ $feature }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mdc-price-box">
                                    <div class="mdc-price-row">
                                        <div class="addon-qty-controller">
                                            <button type="button" class="qty-btn"
                                                    onclick="changeSubQty({{ $addon->id }}, 1)">+
                                            </button>
                                            <input type="number" min="1" value="1"
                                                   id="sub-qty-mobile-{{ $addon->id }}"
                                                   class="qty-input addon-qty-input"
                                                   oninput="manualSubQty({{ $addon->id }}, this.value)">
                                            <button type="button" class="qty-btn"
                                                    onclick="changeSubQty({{ $addon->id }}, -1)">−
                                            </button>
                                        </div>
                                        <div class="mdc-price-info">
                                            <div class="original-box-price">
                                                <span
                                                    class="pcd-original-price">{{ toPersian(number_format($addon->price)) }}</span>
                                                <span>تومان</span>
                                            </div>
                                            <div class="pcd-off-price">
                                                {{ toPersian(number_format($addon->off_price)) }}
                                                <span>تومان</span>
                                            </div>
                                            <div class="pcd-savings">
                                                شما {{ toPersian(number_format($addon->price - $addon->off_price)) }}
                                                تومان کمتر پرداخت می‌کنید!
                                            </div>
                                        </div>
                                    </div>

                                    @if($addon->discount_ends_at && $addon->discount_ends_at->isFuture())
                                        <div class="pcd-timer" id="discount-timer-mobile-{{ $addon->id }}">
                                            <span>مانده تا اتمام تخفیف!</span>
                                            <span class="pcd-timer-display" id="timer-display-mobile-{{ $addon->id }}">--:--:--</span>
                                        </div>
                                    @endif
                                </div>

                            </div>

                        @endif

                    </div>

                    <div class="actions addon-actions-bar">
                        <div class="addon-right-action desktop-back">
                            <button class="btn btn-back-text" type="button" onclick="prevStep()">بازگشت →</button>
                        </div>
                        <div class="addon-left-actions">
                            <button class="btn btn-secondary btn-skip" type="button" onclick="nextStep()">رد و ادامه
                            </button>
                            <button class="btn btn-primary" type="button" onclick="addAddonAndNext({{ $addon->id }})">
                                افزودن به سفارش
                            </button>
                        </div>
                        <div class="addon-right-action mobile-back">
                            <button class="btn btn-back-text" type="button" onclick="prevStep()">بازگشت →</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- =================== STEP 2 =================== -->
        <div class="section" id="section-2">
            <div class="title-area">
                <h1 class="main-title">ثبت مشخصات</h1>
                {{--                <p class="subtitle">اطلاعات شخصی و آدرس خود را وارد کنید.</p>--}}
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>نام</label>
                    <input type="text" id="first-name" placeholder="محمد">
                    <div class="error-message" id="first-name-error">
                        <img src="{{ asset('icon/Danger-Bold-32px.svg') }}"> نام باید وارد شود
                    </div>
                </div>
                <div class="form-group">
                    <label>نام خانوادگی</label>
                    <input type="text" id="last-name" placeholder="حیدری">
                    <div class="error-message" id="last-name-error">
                        <img src="{{ asset('icon/Danger-Bold-32px.svg') }}"> نام خانوادگی باید وارد شود
                    </div>
                </div>
                <div class="form-group full-width">
                    <label>شماره موبایل</label>
                    <input type="tel" id="mobile" placeholder="09123456789">
                    <div class="error-message" id="mobile-error">
                        <img src="{{ asset('icon/Danger-Bold-32px.svg') }}"> شماره تماس باید وارد شود
                    </div>
                </div>
                <div class="form-group">
                    <label>استان</label>
                    <select id="province" name="province_id" style="display:none;">
                        <option value="">انتخاب استان</option>
                        @foreach($provinces as $id => $title)
                            <option value="{{ $id }}">{{ $title }}</option>
                        @endforeach
                    </select>
                    <div class="custom-select" id="province-custom">
                        <div class="custom-select-trigger" onclick="toggleDropdown('province-custom')">
                            <span class="custom-select-value">انتخاب استان</span>
                            <span class="custom-select-arrow">▾</span>
                        </div>
                        <div class="custom-select-dropdown">
                            @foreach($provinces as $id => $title)
                                <div class="custom-select-option" data-id="{{ $id }}"
                                     onclick="selectProvinceOption({{ $id }}, '{{ $title }}')">{{ $title }}</div>
                            @endforeach
                        </div>
                    </div>
                    <div class="error-message" id="province-error">
                        <img src="{{ asset('icon/Danger-Bold-32px.svg') }}"> استان باید وارد شود
                    </div>
                </div>

                <div class="form-group">
                    <label>شهر</label>
                    <select id="city" name="city_id" style="display:none;">
                        <option value="">ابتدا استان را انتخاب کنید</option>
                    </select>
                    <div class="custom-select" id="city-custom">
                        <div class="custom-select-trigger" onclick="toggleDropdown('city-custom')">
                            <span class="custom-select-value">ابتدا استان را انتخاب کنید</span>
                            <span class="custom-select-arrow">▾</span>
                        </div>
                        <div class="custom-select-dropdown" id="city-custom-dropdown">
                        </div>
                    </div>
                    <div class="error-message" id="city-error">
                        <img src="{{ asset('icon/Danger-Bold-32px.svg') }}"> شهر باید وارد شود
                    </div>
                </div>

                <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

                <script>
                    const allCities = {!! json_encode($allCities, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) !!};
                    $('#province').on('change', function () {

                        let provinceId = $(this).val();

                        $('#city').html('<option>در حال دریافت...</option>');

                        if (provinceId) {

                            $.ajax({
                                url: '/get-cities/' + provinceId,
                                type: 'GET',

                                success: function (cities) {

                                    $('#city').html(
                                        '<option value="">انتخاب شهر</option>'
                                    );

                                    $.each(cities, function (key, city) {

                                        $('#city').append(
                                            `<option value="${city.id}">
                                ${city.title}
                            </option>`
                                        );

                                    });

                                }
                            });

                        } else {

                            $('#city').html(
                                '<option value="">ابتدا استان را انتخاب کنید</option>'
                            );

                        }

                    });
                </script>
                <div class="form-group full-width">
                    <label>آدرس کامل</label>
                    <textarea id="address" placeholder="خیابان، کوچه، پلاک ..."></textarea>
                    <div class="error-message" id="address-error">
                        <img src="{{ asset('icon/Danger-Bold-32px.svg') }}"> آدرس کامل باید وارد شود
                    </div>
                </div>
            </div>
            <div class="step2-actions">
                <div class="right-action">
                    <button class="btn btn-back-text" type="button" onclick="prevStep()">→ بازگشت</button>
                </div>
                <div class="left-action">
                    <button class="btn btn-primary" type="button" onclick="nextStep()">مرحله بعدی</button>
                </div>
            </div>
        </div>

        <!-- =================== STEP 3 =================== -->
        <div class="section" id="section-3">
            <div class="title-area">
                <h1 class="main-title">انتخاب روش ارسال</h1>
            </div>
            <div class="delivery-wrapper">
                <div class="delivery-card active-delivery" data-delivery="express"
                     onclick="selectDelivery(this, 30000)">
                    <div class="delivery-header">
                        <div class="delivery-header-right">
                            <span class="delivery-icon"><img
                                    src="{{ asset('icon/TruckFast-TwoTone-32px.svg') }}"></span>
                            <span class="delivery-title">پیک فوری (فقط تهران)</span>
                        </div>
                        <div class="delivery-price">
                            <span class="delivery-price-label">هزینه ارسال: </span>
                            <span class="delivery-price-value">پس کرایه</span>
                        </div>
                    </div>
                    <div class="delivery-form">
                        <div class="delivery-select-group">
                            <label>روز تحویل</label>
                            <div class="custom-select" id="day-select">
                                <div class="custom-select-trigger" onclick="toggleDropdown('day-select')">
                                    <span class="custom-select-value">انتخاب روز</span>
                                    <span class="custom-select-arrow">▾</span>
                                </div>
                                <div class="custom-select-dropdown" id="day-dropdown"></div>
                                <input type="hidden" id="delivery-day" value="">
                            </div>
                        </div>
                        <div class="delivery-select-group">
                            <label>ساعت تحویل</label>
                            <div class="custom-select" id="time-select">
                                <div class="custom-select-trigger" onclick="toggleDropdown('time-select')">
                                    <span class="custom-select-value">انتخاب ساعت</span>
                                    <span class="custom-select-arrow">▾</span>
                                </div>
                                <div class="custom-select-dropdown" id="time-dropdown">
                                </div>
                                <input type="hidden" id="delivery-time" value="">
                            </div>
                        </div>
                    </div>
                    <div class="delivery-error" id="delivery-error">لطفاً روز و ساعت تحویل را انتخاب کنید</div>
                </div>
                <div class="delivery-card disabled-delivery" data-delivery="tipax"
                     onclick="selectDelivery(this, 80000)">
                    <div class="delivery-header">
                        <div class="delivery-header-right">
                            <span class="delivery-icon"><img src="{{ asset('icon/Box-TwoTone-32px.svg') }}"></span>
                            <span class="delivery-title">تیپاکس</span>
                        </div>
                        <div class="delivery-price">
                            <span class="delivery-price-label">هزینه ارسال: </span>
                            <span class="delivery-price-value">پس کرایه</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="step3-actions">
                <div class="right-action">
                    <button class="btn btn-back-text" type="button" onclick="prevStep()">بازگشت →</button>
                </div>
                <div class="left-action">
                    <button class="btn btn-primary" type="button" onclick="nextStep()">← مرحله بعدی</button>
                </div>
            </div>
        </div>

        <!-- =================== STEP 4 =================== -->
        <div class="section" id="section-4">
            <div class="sub-step" id="sub-step-4-1">
                <div class="title-area">
                    <h1 class="main-title">تایید اطلاعات گیرنده</h1>
                </div>
                <div class="confirm-card">
                    <div class="confirm-row">
                        <div class="confirm-label">دریافت کننده</div>
                        <div class="confirm-value editable-field">
                            <input type="text" id="confirm-fullname" class="confirm-inline-input" value="" readonly>
                            <button type="button" class="inline-edit-btn"
                                    onclick="enableInlineEdit('confirm-fullname')">
                                <img src="{{ asset('icon/Edit2-TwoTone-32px.svg') }}" width="20" height="20">
                            </button>
                        </div>
                    </div>
                    <div class="confirm-row">
                        <div class="confirm-label">شماره تماس</div>
                        <div class="confirm-value editable-field">
                            <input type="text" id="confirm-mobile" class="confirm-inline-input" value="" readonly>
                            <button type="button" class="inline-edit-btn" onclick="enableInlineEdit('confirm-mobile')">
                                <img src="{{ asset('icon/Edit2-TwoTone-32px.svg') }}" width="20" height="20">
                            </button>
                        </div>
                    </div>
                    <div class="confirm-row">
                        <div class="confirm-label">آدرس</div>
                        <div class="confirm-value editable-field">
                            <textarea id="confirm-address" class="confirm-inline-textarea" readonly></textarea>
                            <button type="button" class="inline-edit-btn" onclick="enableInlineEdit('confirm-address')">
                                <img src="{{ asset('icon/Edit2-TwoTone-32px.svg') }}" width="20" height="20">
                            </button>
                        </div>
                    </div>
                    <div class="confirm-row">
                        <div class="confirm-label">روش ارسال</div>
                        <div class="confirm-value editable-field">
                            <select id="confirm-delivery" class="confirm-inline-select" disabled
                                    onchange="toggleConfirmDeliveryFields()">
                                <option value="express">پیک فوری</option>
                                <option value="tipax">تیپاکس</option>
                            </select>
                            <button type="button" class="inline-edit-btn"
                                    onclick="enableInlineSelect('confirm-delivery')">
                                <img src="{{ asset('icon/Edit2-TwoTone-32px.svg') }}" width="20" height="20">
                            </button>
                        </div>
                    </div>
                    <div id="confirm-express-fields">
                        <div class="confirm-row">
                            <div class="confirm-label">روز تحویل</div>
                            <div class="confirm-value editable-field">
                                <div class="confirm-custom-select" id="confirm-day-wrap">
                                    <div class="confirm-custom-trigger disabled-trigger" id="confirm-day-trigger">
                                        <span id="confirm-delivery-day-text">انتخاب روز</span>
                                        <span class="custom-select-arrow">▾</span>
                                    </div>
                                    <div class="confirm-custom-dropdown" id="confirm-day-dropdown"></div>
                                    <input type="hidden" id="confirm-delivery-day" value="">
                                </div>
                                <button type="button" class="inline-edit-btn"
                                        onclick="toggleConfirmSelectEdit('confirm-day-wrap', 'confirm-day-trigger')"
                                        id="btn-edit-confirm-day-wrap">
            <span id="icon-edit-confirm-day-wrap">
                <img src="{{ asset('icon/Edit2-TwoTone-32px.svg') }}" width="20" height="20">
            </span>
                                </button>
                            </div>
                        </div>
                        <div class="confirm-row">
                            <div class="confirm-label">ساعت تحویل</div>
                            <div class="confirm-value editable-field">
                                <div class="confirm-custom-select" id="confirm-time-wrap">
                                    <div class="confirm-custom-trigger disabled-trigger" id="confirm-time-trigger">
                                        <span id="confirm-delivery-time-text">انتخاب ساعت</span>
                                        <span class="custom-select-arrow">▾</span>
                                    </div>
                                    <div class="confirm-custom-dropdown" id="confirm-time-dropdown"></div>
                                    <input type="hidden" id="confirm-delivery-time" value="">
                                </div>
                                <button type="button" class="inline-edit-btn"
                                        onclick="toggleConfirmSelectEdit('confirm-time-wrap', 'confirm-time-trigger')"
                                        id="btn-edit-confirm-time-wrap">
                <span id="icon-edit-confirm-time-wrap">
                    <img src="{{ asset('icon/Edit2-TwoTone-32px.svg') }}" width="20" height="20">
                </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="step4-actions">
                    <div class="right-action">
                        <button class="btn btn-back-text" type="button" onclick="prevStep()">بازگشت →</button>
                    </div>
                    <div class="left-action">
                        <button class="btn btn-primary" type="button" onclick="nextStep()">← مرحله بعدی</button>
                    </div>
                </div>
            </div>

            <!-- زیرمرحله دوم: فاکتور نهایی اقلام -->
            <div class="sub-step" id="sub-step-4-2">
                <div class="title-area">
                    <h1 class="main-title">تایید نهایی اطلاعات</h1>
                </div>
                <div class="invoice-card">
                    <div class="invoice-section-title">محصولات انتخابی:</div>
                    <div id="invoice-editable-list"></div>
                    <div class="invoice-total-bottom">
                        <span class="invoice-total-label">مبلغ کل واریزی:</span>
                        <span class="invoice-total-amount"><strong id="invoice-final-price">۰</strong> تومان</span>
                    </div>
                </div>
                <div class="step4-actions">
                    <div class="right-action">
                        <button class="btn btn-back-text" type="button" onclick="prevStep()">بازگشت →</button>
                    </div>
                    <div class="left-action">
                        <button class="btn btn-primary" type="button" onclick="nextStep()">← ادامه و پرداخت</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- =================== STEP 5 =================== -->
        <div class="section" id="section-5">
            <div class="title-area">
                <h1 class="main-title">آپلود رسید پرداخت</h1>
                <p class="subtitle">لطفاً تصویر فیش واریزی را بارگذاری کنید.</p>
            </div>

            <!-- دسکتاپ -->
            <div class="receipt-info-box desktop-receipt">
                <div class="receipt-card-details">
                    <div class="card-number-wrapper">
                        <span class="card-number-label">شماره کارت:</span>
                        <span class="card-number-value">۶۰۳۷۹۹۱۸۵۰۰۰۱۲۳۴</span>
                        <button type="button" class="copy-btn" onclick="copyCardNumber('۶۰۳۷۹۹۱۸۵۰۰۰۱۲۳۴')">
                            <img src="{{ asset('icon/copy.svg') }}" width="20" height="20">
                        </button>
                    </div>
                    <div class="bank-name">(بانک صادرات - محمدرسول حیدری)</div>
                </div>
                <div class="receipt-amount">
                    <span class="mobile-receipt-label">مبلغ واریزی:</span>
                    <strong id="final-payable-amount">۰</strong>
                    <span class="receipt-amount-unit">تومان</span>
                </div>
            </div>

            <!-- موبایل -->
            <div class="mob-receipt mobile-receipt">
                <div class="mob-row">
                    <span class="mob-label">مبلغ واریزی:</span>
                    <div class="mob-val">
                        <span class="mob-unit">تومان</span>
                        <strong id="final-payable-amount-mobile">۰</strong>
                    </div>
                </div>
                <div class="mob-row">
                    <span class="mob-label">شماره کارت:</span>
                    <div class="mob-val">
                        <button type="button" class="copy-btn" onclick="copyCardNumber('۶۰۳۷۹۹۱۸۵۰۰۰۱۲۳۴')">
                            <img src="{{ asset('icon/copy.svg') }}" width="18" height="18">
                        </button>
                        <span class="mob-card-num">۶۰۳۷۹۹۱۸۵۰۰۰۱۲۳۴</span>
                    </div>
                </div>
                <div class="mob-bank">(بانک صادرات - محمدرسول حیدری)</div>
            </div>

            <!-- آپلود -->
            <div class="upload-wrapper" style="margin-top: 20px;">
                <div class="upload-box" id="uploadBox">
                    <input type="file" id="imageInput" name="images[]"
                           accept="image/png, image/jpeg" multiple hidden>
                    <div class="upload-content">
                        <img src="{{ asset('icon/document-upload.svg') }}" width="48" height="48"
                             class="upload-icon-img">
                        <p>کلیک کنید یا فایل رسید واریزی را اینجا رها کنید</p>
                    </div>
                </div>
                <div class="error-message" id="image-error">
                    <img src="{{ asset('icon/Danger-Bold-32px.svg') }}"> حداقل یک تصویر باید آپلود شود
                </div>
                <div class="preview-grid" id="previewGrid"></div>
            </div>

            <div class="step5-actions">
                <div class="right-action">
                    <button class="btn btn-back-text" type="button" onclick="prevStep()">بازگشت →</button>
                </div>
                <div class="left-action">
                    <button class="btn btn-success" type="button" onclick="nextStep()">← ثبت نهایی فیش</button>
                </div>
            </div>
        </div>

        <!-- =================== STEP 6 =================== -->
        <div class="section" id="section-success" style="display:none;">
            <div class="success-check-wrap">
                <div class="success-circle">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                        <path d="M5 13l4 4L19 7" stroke="white" stroke-width="2.5" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="success-dots">
                    <span></span><span></span><span></span><span></span>
                    <span></span><span></span><span></span><span></span>
                </div>
            </div>

            <div class="success-title-wrap">
                <h1 class="success-main-title">سفارش شما با موفقیت ثبت شد!</h1>
            </div>

            <p class="subtitle" style="text-align:center; margin-top:12px;">
                سفارش شما با شماره
                <strong>{{ session('order_id') }}</strong>
                با موفقیت ثبت شد و به زودی آماده پردازش خواهد شد. جزئیات سفارش برای شما ارسال گردید.
            </p>

            <div class="success-info-box">
                <div class="success-info-item success-item-amount">
                    <div class="success-info-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path d="M21 12V7H5a2 2 0 010-4h14v4M5 7v13a2 2 0 002 2h14v-5" stroke="#0a89d9"
                                  stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="success-info-texts">
                        <div class="success-info-label">مبلغ پرداختی</div>
                        <div class="success-info-value">{{ toPersian(number_format(session('order_total', 0))) }}
                            تومان
                        </div>
                    </div>
                </div>
                <div class="success-info-divider success-div-date"></div>
                <div class="success-info-item success-item-date">
                    <div class="success-info-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M8 2v3M16 2v3M3 8h18M5 4h14a2 2 0 012 2v13a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"
                                stroke="#0a89d9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="success-info-texts">
                        <div class="success-info-label">تاریخ ثبت سفارش</div>
                        <div class="success-info-value">{{ session('order_date', '—') }}</div>
                    </div>
                </div>
                <div class="success-info-divider success-div-id"></div>
                <div class="success-info-item success-item-id">
                    <div class="success-info-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path d="M9 12h6M9 16h6M17 21H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"
                                  stroke="#0a89d9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="success-info-texts">
                        <div class="success-info-label">شماره سفارش</div>
                        <div class="success-info-value">{{ toPersian(session('order_id', '—')) }}</div>
                    </div>
                </div>
            </div>

            @if(session('telegram_link'))
                <div style="text-align: center; margin-top: 30px;">

                    @if(session('is_iran_ip'))
                        <div class="vpn-warning" id="vpn-warning-main">
                            <div class="vpn-warning-icon">🔒</div>
                            <div class="vpn-warning-text">
                                <strong>برای ثبت نهایی سفارش به VPN نیاز دارید</strong>
                                <span>VPN خود را روشن کنید، سپس دکمه زیر را بزنید.</span>
                            </div>
                            <button onclick="document.getElementById('vpn-warning-main').style.display='none'"
                                    class="vpn-close-btn">✕
                            </button>
                        </div>

                        <div id="vpn-check-msg" style="display:none; margin-top:16px; margin-bottom:16px;"
                             class="vpn-warning">
                            <div class="vpn-warning-icon">⚠️</div>
                            <div class="vpn-warning-text">
                                <strong>آیا تلگرام باز شد؟</strong>
                                <span>اگر صفحه‌ای باز نشد، VPN خود را روشن کنید و دوباره امتحان کنید.</span>
                            </div>
                            <button onclick="document.getElementById('vpn-check-msg').style.display='none'"
                                    class="vpn-close-btn">✕
                            </button>
                        </div>
                    @endif

                    <a href="{{ session('telegram_link') }}"
                       target="_blank"
                       id="telegram-btn"
                       class="btn btn-primary"
                       style="display:inline-block; padding:14px 30px; border-radius:30px; font-size:17px; font-weight:bold; width:fit-content; text-decoration:none;">
                        ثبت نهایی سفارش
                    </a>

                    @if(session('is_iran_ip'))
                        <script>
                            document.getElementById('telegram-btn').addEventListener('click', function () {
                                setTimeout(function () {
                                    document.getElementById('vpn-check-msg').style.display = 'flex';
                                }, 2000);
                            });
                        </script>
                    @endif

                </div>
            @endif

            {{--            <div class="success-notice">--}}
            {{--                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">--}}
            {{--                    <circle cx="12" cy="12" r="10" stroke="#16a34a" stroke-width="1.5"/>--}}
            {{--                    <path d="M12 8v4M12 16h.01" stroke="#16a34a" stroke-width="1.5" stroke-linecap="round"/>--}}
            {{--                </svg>--}}
            {{--                <span>کد رهگیری سفارش از طریق پیامک برای شما ارسال گردید.</span>--}}
            {{--            </div>--}}
        </div>

        {{-- ======================================================
             تغییر ۳: hidden input های لازم برای ارسال به سرور
             ====================================================== --}}
        <input type="hidden" name="full_name" id="hidden-full-name">
        <input type="hidden" name="phone" id="hidden-phone">
        <input type="hidden" name="city_id" id="hidden-city">
        <input type="hidden" name="address" id="hidden-address">
        <input type="hidden" name="prd_qty" id="hidden-prd-qty" value="1">
        <input type="hidden" name="shipping_method" id="hidden-shipping-method" value="express">
        <input type="hidden" name="shipping_time" id="hidden-shipping-time">

        <!-- =================== ACTIONS =================== -->
        <div class="actions" id="actions-bar">
            <div class="left-actions">
                <button class="btn btn-primary" id="btn-next" type="button" onclick="nextStep()">ادامه</button>
                <button class="btn btn-secondary" id="btn-prev" type="button" onclick="prevStep()" disabled>بازگشت
                </button>
            </div>
        </div>
    </div>
</form>

<script>

    @if($mainProduct->off_price && $mainProduct->discount_ends_at && $mainProduct->discount_ends_at->isFuture())
    (function () {
        const endTime = new Date("{{ $mainProduct->discount_ends_at->toIso8601String() }}").getTime();

        function tick() {
            const diff = endTime - Date.now();
            const desktopEl = document.getElementById('timer-display');
            const mobileEl = document.getElementById('timer-display-mobile');

            if (diff <= 0) {
                const d1 = document.getElementById('discount-timer');
                const d2 = document.getElementById('discount-timer-mobile');
                if (d1) d1.style.display = 'none';
                if (d2) d2.style.display = 'none';
                return;
            }

            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            const pad = n => String(n).padStart(2, '0');
            const text = `${pad(s)} : ${pad(m)} : ${pad(h)}`;

            if (desktopEl) desktopEl.textContent = text;
            if (mobileEl) mobileEl.textContent = text;
        }

        tick();
        setInterval(tick, 1000);
    })();
    @endif

        @foreach($addOns as $addon)
        @if($addon->off_price && $addon->discount_ends_at && $addon->discount_ends_at->isFuture())
    (function () {
        const endTime = new Date("{{ $addon->discount_ends_at->toIso8601String() }}").getTime();
        const timerEl = document.getElementById('pcd-timer-display-{{ $addon->id }}');
        if (!timerEl) return;

        function tick() {
            const diff = endTime - Date.now();
            if (diff <= 0) {
                const box = document.getElementById('pcd-timer-{{ $addon->id }}');
                if (box) box.style.display = 'none';
                return;
            }
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            const pad = n => String(n).padStart(2, '0');
            timerEl.textContent = `${pad(h)} : ${pad(m)} : ${pad(s)}`;
        }

        tick();
        setInterval(tick, 1000);
    })();
    @endif
        @endforeach

        @foreach($addOns as $addon)
        @if($addon->off_price && $addon->discount_ends_at && $addon->discount_ends_at->isFuture())
    (function () {
        const endTime = new Date("{{ $addon->discount_ends_at->toIso8601String() }}").getTime();

        function tick() {
            const diff = endTime - Date.now();
            const d1 = document.getElementById('timer-display-{{ $addon->id }}');
            const d2 = document.getElementById('timer-display-mobile-{{ $addon->id }}');

            if (diff <= 0) {
                const b1 = document.getElementById('discount-timer-{{ $addon->id }}');
                const b2 = document.getElementById('discount-timer-mobile-{{ $addon->id }}');
                if (b1) b1.style.display = 'none';
                if (b2) b2.style.display = 'none';
                return;
            }

            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            const pad = n => String(n).padStart(2, '0');
            const text = `${pad(s)} : ${pad(m)} : ${pad(h)}`;

            if (d1) d1.textContent = text;
            if (d2) d2.textContent = text;
        }

        tick();
        setInterval(tick, 1000);
    })();
    @endif
    @endforeach



    document.addEventListener('DOMContentLoaded', function () {
        try {
            buildDayOptions();
        } catch (e) {
            console.error('buildDayOptions:', e);
        }
        try {
            buildConfirmDayOptions();
        } catch (e) {
            console.error('buildConfirmDayOptions:', e);
        }
        try {
            buildTimeOptions();
        } catch (e) {
            console.error('buildTimeOptions:', e);
        }
        try {
            buildConfirmTimeOptions();
        } catch (e) {
            console.error('buildConfirmTimeOptions:', e);
        }
    });

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/service-worker.js');
        });
    }
    const stepStructure = {
        1: {
            subSteps: {{ $addOns->count() + 1 }},
            currentSub: 1
        },
        2: {subSteps: 1, currentSub: 1},
        3: {subSteps: 1, currentSub: 1},
        4: {subSteps: 2, currentSub: 1},
        5: {subSteps: 1, currentSub: 1}
    };

    const DEFAULT_CARD = {
        number: '۵۸۹۴۶۳۱۱۳۹۲۲۲۸۹۷',
        numberEn: '5894631139222897',
        bank: 'بانک رفاه کارگران',
        owner: 'محمدرسول حیدری',
    };

    const SECOND_CARD = {
        number: '۶۰۳۷۶۹۷۶۹۸۴۸۴۴۰۶',
        numberEn: '6037697698484406',
        bank: 'بانک صادرات',
        owner: 'محمد مهدی خبیری',
    };

    function updateCardInfo(totalSum) {
        const card = totalSum > 10000000 ? SECOND_CARD : DEFAULT_CARD;

        // دسکتاپ
        document.querySelector('.card-number-value').textContent = card.number;
        document.querySelector('.bank-name').textContent = `(${card.bank} - ${card.owner})`;

        // موبایل
        document.querySelector('.mob-card-num').textContent = card.number;
        document.querySelector('.mob-bank').textContent = `(${card.bank} - ${card.owner})`;

        // دکمه‌های کپی
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.setAttribute('onclick', `copyCardNumber('${card.numberEn}')`);
        });
    }

    let currentStep = 1;
    let mainProductPrice = {{ $mainProduct->price }};
    let mainQty = 1;

    let subProducts = [
            @foreach($addOns as $addon)
        {
            id: {{ $addon->id }},
            name: "{{ $addon->name }}",
            price: {{ $addon->off_price ?: $addon->price }},
            qty: 0
        },
        @endforeach
    ];

    let selectedDelivery = 'express';

    const persianNums = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    function toPersian(n) {
        return String(n).replace(/\d/g, d => persianNums[d]);
    }

    function formatMoney(num) {
        return num.toLocaleString('fa-IR');
    }

    function changeMainQty(value) {
        let val = parseInt(value);
        mainQty = (isNaN(val) || val < 1) ? '' : val;

        const qtyDesktop = document.getElementById('qty');
        const qtyMobile = document.getElementById('qty-mobile');
        if (qtyDesktop) qtyDesktop.value = mainQty;
        if (qtyMobile) qtyMobile.value = mainQty;

        calculateTotalInvoice();
    }

    function increaseMainQty() {
        mainQty++;
        document.getElementById('qty').value = mainQty;
        calculateTotalInvoice();
    }

    function decreaseMainQty() {
        if (mainQty > 1) {
            mainQty--;
            document.getElementById('qty').value = mainQty;
            calculateTotalInvoice();
        }
    }

    function changeSubQty(id, step) {
        let sub = subProducts.find(p => p.id === id);
        if (sub) {
            let newQty = sub.qty + step;
            if (newQty >= 1) {
                sub.qty = newQty;
                syncSubQtyInputs(id, newQty);
                calculateTotalInvoice();
            }
        }
    }

    function syncSubQtyInputs(id, qty) {
        const main = document.getElementById(`sub-qty-${id}`);
        const mobile = document.getElementById(`sub-qty-mobile-${id}`);
        if (main) main.value = qty;
        if (mobile) mobile.value = qty;
    }

    function calculateTotalInvoice() {
        const container = document.getElementById('invoice-editable-list');
        if (!container) return;

        container.innerHTML = '';
        let totalSum = 0;

        // محصول اصلی
        let mainTotal = mainProductPrice * mainQty;
        totalSum += mainTotal;

        container.innerHTML += `
        <div class="invoice-product-item">
            <div class="invoice-product-name">{{ $mainProduct->name }}</div>
            <div class="invoice-qty-controller">
                <button class="invoice-qty-btn" type="button" onclick="changeInvoiceMainQty(-1)">−</button>
                <input type="number" class="invoice-qty-input" value="${mainQty}" min="1" oninput="setInvoiceMainQty(this.value)">
                <button class="invoice-qty-btn" type="button" onclick="changeInvoiceMainQty(1)">+</button>
            </div>
        </div>
        <div class="invoice-divider"></div>
    `;

        // محصولات مکمل
        subProducts.forEach((sub, index) => {
            let subTotal = sub.price * sub.qty;
            totalSum += subTotal;

            const isZero = sub.qty === 0;
            const isOne = sub.qty === 1;

            let leftBtn = '';
            if (isZero) {
                leftBtn = `
                <button class="invoice-qty-btn invoice-qty-btn-delete-disabled" type="button" disabled>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M21 5.98c-3.33-.33-6.68-.5-10.02-.5-1.98 0-3.96.1-5.94.3L3 5.98M8.5 4.97l.22-1.31C8.88 2.71 9 2 10.69 2h2.62c1.69 0 1.82.75 1.97 1.67l.22 1.3M18.85 9l-.65 10.07C18.09 20.78 18 22 15.21 22H8.79C6 22 5.91 20.78 5.8 19.07L5.15 9M10.33 16.5h3.33M9.5 12.5h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>`;
            } else if (isOne) {
                leftBtn = `
                <button class="invoice-qty-btn invoice-qty-btn-delete" type="button" onclick="changeInvoiceSubQty(${sub.id}, -1)">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M21 5.98c-3.33-.33-6.68-.5-10.02-.5-1.98 0-3.96.1-5.94.3L3 5.98M8.5 4.97l.22-1.31C8.88 2.71 9 2 10.69 2h2.62c1.69 0 1.82.75 1.97 1.67l.22 1.3M18.85 9l-.65 10.07C18.09 20.78 18 22 15.21 22H8.79C6 22 5.91 20.78 5.8 19.07L5.15 9M10.33 16.5h3.33M9.5 12.5h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>`;
            } else {
                leftBtn = `<button class="invoice-qty-btn" type="button" onclick="changeInvoiceSubQty(${sub.id}, -1)">−</button>`;
            }

            container.innerHTML += `
            <div class="invoice-product-item ${index === 0 ? 'invoice-addon-first' : ''}">
                <div class="invoice-product-name">${sub.name}</div>
                <div class="invoice-qty-controller">
                    ${leftBtn}
                    <input type="number" class="invoice-qty-input" value="${sub.qty}" min="0" oninput="setInvoiceSubQty(${sub.id}, this.value)">
                    <button class="invoice-qty-btn" type="button" onclick="changeInvoiceSubQty(${sub.id}, 1)">+</button>
                </div>
            </div>
        `;
        });

        document.getElementById('invoice-final-price').textContent = `${formatMoney(totalSum)}`;
        document.getElementById('final-payable-amount').textContent = formatMoney(totalSum);
        const mobileAmount = document.getElementById('final-payable-amount-mobile');
        if (mobileAmount) mobileAmount.textContent = formatMoney(totalSum);

        updateCardInfo(totalSum);
    }

    function changeInvoiceMainQty(step) {
        let newQty = mainQty + step;
        if (newQty < 1) return;
        mainQty = newQty;
        document.getElementById('qty').value = mainQty;
        calculateTotalInvoice();
    }

    function setInvoiceMainQty(value) {
        let qty = parseInt(value);
        if (isNaN(qty) || qty < 1) qty = 1;
        mainQty = qty;
        document.getElementById('qty').value = qty;
        calculateTotalInvoice();
    }

    function changeInvoiceSubQty(id, step) {
        let sub = subProducts.find(s => s.id === id);
        if (!sub) return;
        let newQty = sub.qty + step;
        if (newQty < 0) newQty = 0;
        sub.qty = newQty;
        document.getElementById(`sub-qty-${id}`).value = newQty;
        calculateTotalInvoice();
    }

    function setInvoiceSubQty(id, value) {
        let sub = subProducts.find(s => s.id === id);
        if (!sub) return;
        let qty = parseInt(value);
        if (isNaN(qty) || qty < 0) qty = 0;
        sub.qty = qty;
        document.getElementById(`sub-qty-${id}`).value = qty;
        calculateTotalInvoice();
    }

    function selectDelivery(card, cost) {
        document.querySelectorAll('.delivery-card').forEach(c => {
            c.classList.remove('active-delivery');
            c.classList.add('disabled-delivery');
        });
        card.classList.remove('disabled-delivery');
        card.classList.add('active-delivery');
        selectedDelivery = card.dataset.delivery;
        calculateTotalInvoice();
    }

    function fillConfirmData() {
        const firstName = document.getElementById('first-name').value;
        const lastName = document.getElementById('last-name').value;
        const mobile = document.getElementById('mobile').value;
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const province = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
        const city = citySelect.options[citySelect.selectedIndex]?.text || '';
        const address = document.getElementById('address').value;

        document.getElementById('confirm-fullname').value = firstName + ' ' + lastName;
        document.getElementById('confirm-mobile').value = mobile;
        document.getElementById('confirm-address').value =
            province + ' - ' + city + ' - ' + address;
        document.getElementById('confirm-delivery').value = selectedDelivery;

        if (selectedDelivery === 'express') {
            const day = document.getElementById('delivery-day').value;
            const time = document.getElementById('delivery-time').value;

            document.getElementById('confirm-delivery-day').value = day;
            document.getElementById('confirm-delivery-time').value = time;

            const dayText = document.getElementById('confirm-delivery-day-text');
            const timeText = document.getElementById('confirm-delivery-time-text');
            if (dayText) dayText.textContent = day || 'انتخاب روز';
            if (timeText) timeText.textContent = time || 'انتخاب ساعت';

            buildConfirmTimeOptions();
        }
        toggleConfirmDeliveryFields();
        calculateTotalInvoice();
    }

    function toggleConfirmDeliveryFields() {
        const type = document.getElementById('confirm-delivery').value;
        document.getElementById('confirm-express-fields').style.display = (type === 'express') ? 'block' : 'none';
    }

    function updateFormUI() {
        for (let i = 1; i <= 5; i++) {
            const dot = document.getElementById(`step-dot-${i}`);
            if (i < currentStep) {
                dot.className = 'step done';
            } else if (i === currentStep) {
                dot.className = 'step active';
            } else {
                dot.className = 'step';
            }

            if (i < 5) {
                document.getElementById(`line-${i}`).className = i < currentStep ? 'line active' : 'line';
            }
        }

        for (let i = 1; i <= 5; i++) {
            const sec = document.getElementById(`section-${i}`);
            if (sec) sec.classList.toggle('active', i === currentStep);
        }

        const currentStepConfig = stepStructure[currentStep];
        const actionsBar = document.getElementById('actions-bar');
        const isAddonSub = (currentStep === 1 && stepStructure[1].currentSub > 1);
        const isStep2 = currentStep === 2;
        const isStep3 = currentStep === 3;
        const isStep4 = currentStep === 4;
        const isStep5 = currentStep === 5;
        actionsBar.style.display = (isAddonSub || isStep2 || isStep3 || isStep4 || isStep5) ? 'none' : 'flex';


        if (currentStepConfig.subSteps > 1) {
            for (let s = 1; s <= currentStepConfig.subSteps; s++) {
                const subEl = document.getElementById(`sub-step-${currentStep}-${s}`);
                if (subEl) subEl.classList.toggle('active-sub', s === currentStepConfig.currentSub);
            }
        } else {
            const allSubsInSection = document.querySelectorAll(`#section-${currentStep} .sub-step`);
            allSubsInSection.forEach(el => el.classList.remove('active-sub'));
            // اگه فقط یه sub-step داریم، اونو active کن
            const firstSub = document.getElementById(`sub-step-${currentStep}-1`);
            if (firstSub) firstSub.classList.add('active-sub');
        }
        const btnNext = document.getElementById('btn-next');
        const btnPrev = document.getElementById('btn-prev');
        btnPrev.style.display = (currentStep === 1 && stepStructure[1].currentSub === 1) ? 'none' : 'block';
        btnPrev.disabled = (currentStep === 1 && stepStructure[1].currentSub === 1);
        btnNext.textContent = (currentStep === 5) ? 'ثبت نهایی و ارسال فیش' : 'ادامه ←';
    }

    // ======================================================
    // تغییر ۴: در nextStep وقتی به استپ ۵ رسیدیم و دکمه نهایی
    //          زده شد، hidden inputها را پر کرده و فرم سابمیت میشه
    // ======================================================
    function nextStep() {

        if (currentStep === 1 && stepStructure[1].currentSub > 1) {
            const addonIndex = stepStructure[1].currentSub - 2;
            const addon = subProducts[addonIndex];
            if (addon && addon.qty === 0) {
                const input = document.getElementById(`sub-qty-${addon.id}`);
                if (input) input.value = 0;
            }
        }

        if (currentStep === 1 && stepStructure[1].currentSub === 1) {
            const qty = document.getElementById('qty') || document.getElementById('qty-mobile');
            const qtyVal = qty ? qty.value : '';
            if (!qtyVal || parseInt(qtyVal) < 1) {
                showErrorToast('لطفاً تعداد محصول را وارد کنید');
                if (qty) qty.classList.add('input-error');
                return;
            }
            if (qty) qty.classList.remove('input-error');
        }

        if (currentStep === 2 && !validateStep2()) return;
        if (currentStep === 3 && !validateStep3()) return;

        const config = stepStructure[currentStep];

        if (config.currentSub < config.subSteps) {
            config.currentSub++;
            if (currentStep === 4 && config.currentSub === 2) {
                calculateTotalInvoice();
            }
            updateFormUI();
            window.scrollTo({top: 0, behavior: 'smooth'});
        } else {
            if (currentStep < 5) {
                currentStep++;
                stepStructure[currentStep].currentSub = 1;
                if (currentStep === 4) fillConfirmData();
                updateFormUI();
                window.scrollTo({top: 0, behavior: 'smooth'});
            } else {

                if (selectedFiles.length === 0) {
                    imageError.style.display = 'block';
                    return;
                }

                // جلوی double submit
                const submitBtn = document.querySelector('.btn-success');
                if (submitBtn && submitBtn.disabled) return;
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = 'در حال ارسال...';
                }

                document.getElementById('hidden-full-name').value =
                    document.getElementById('confirm-fullname').value;

                document.getElementById('hidden-phone').value =
                    document.getElementById('confirm-mobile').value;

                document.getElementById('hidden-city').value =
                    document.getElementById('city').value;

                document.getElementById('hidden-address').value =
                    document.getElementById('confirm-address').value;

                document.getElementById('hidden-prd-qty').value = mainQty;

                document.getElementById('hidden-shipping-method').value = selectedDelivery;

                document.getElementById('hidden-shipping-time').value =
                    selectedDelivery === 'express'
                        ? (document.getElementById('confirm-delivery-day').value
                            + ' - '
                            + document.getElementById('confirm-delivery-time').value)
                        : '';

                showSuccessToast('در حال ثبت سفارش و آپلود فیش...');

                setTimeout(() => {
                    document.getElementById('main-form').submit();
                }, 1200);

                return;
            }
        }
    }

    const uploadBox = document.getElementById('uploadBox');
    const imageInput = document.getElementById('imageInput');
    const previewGrid = document.getElementById('previewGrid');
    const imageError = document.getElementById('image-error');

    let selectedFiles = [];

    // کلیک روی باکس => باز شدن فایل منیجر
    uploadBox.addEventListener('click', () => {
        imageInput.click();
    });

    // انتخاب فایل
    imageInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    // Drag & Drop
    uploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadBox.classList.add('dragging');
    });

    uploadBox.addEventListener('dragleave', () => {
        uploadBox.classList.remove('dragging');
    });

    uploadBox.addEventListener('drop', (e) => {
        e.preventDefault();

        uploadBox.classList.remove('dragging');

        handleFiles(e.dataTransfer.files);
    });

    function selectProvinceOption(id, title) {
        const select = document.getElementById('province-custom');
        const input = document.getElementById('province');
        select.querySelector('.custom-select-value').textContent = title;
        input.value = id;
        select.querySelectorAll('.custom-select-option').forEach(opt => {
            opt.classList.toggle('selected', opt.textContent.trim() === title);
        });
        select.classList.remove('open');

        const cityCustom = document.getElementById('city-custom');
        const cityDropdown = document.getElementById('city-custom-dropdown');
        const cityInput = document.getElementById('city');

        cityDropdown.innerHTML = '';
        cityInput.value = '';
        cityCustom.querySelector('.custom-select-value').textContent = 'انتخاب شهر';

        const cities = allCities[id] || allCities[String(id)] || [];

        cities.forEach(function (city) {
            const div = document.createElement('div');
            div.className = 'custom-select-option';
            div.textContent = city.title;
            div.setAttribute('data-id', city.id);
            div.addEventListener('click', function () {
                selectCityOption(this.getAttribute('data-id'), this.textContent);
            });
            cityDropdown.appendChild(div);
        });

        if (cities.length === 0) {
            cityCustom.querySelector('.custom-select-value').textContent = 'شهری یافت نشد';
        }
    }

    function selectCityOption(id, title) {
        const input = document.getElementById('city');
        input.value = id;
        input.innerHTML = `<option value="${id}" selected>${title}</option>`;

        const select = document.getElementById('city-custom');
        select.querySelector('.custom-select-value').textContent = title;
        select.querySelectorAll('.custom-select-option').forEach(opt => {
            opt.classList.toggle('selected', opt.textContent.trim() === title);
        });
        select.classList.remove('open');
    }

    function handleFiles(files) {

        [...files].forEach(file => {

            // فقط عکس
            if (!file.type.match('image/jpeg') &&
                !file.type.match('image/png')) {

                showErrorToast('فقط فایل JPG و PNG مجاز است');
                return;
            }

            // حداکثر 5MB
            if (file.size > 5 * 1024 * 1024) {

                showSuccessToast('حجم فایل نباید بیشتر از 5MB باشد');
                return;
            }

            selectedFiles.push(file);

            createPreview(file);

        });

        updateInputFiles();

        imageError.style.display = 'none';
    }

    function buildTimeOptions(selectedDay = null) {
        const dropdown = document.getElementById('time-dropdown');
        const currentHour = new Date().getHours();

        const days = ['یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه'];
        const todayName = days[new Date().getDay()];
        const isToday = selectedDay === todayName || selectedDay === null;

        const slots = [
            {value: '10-12', label: '۱۰ تا ۱۲', start: 10},
            {value: '12-14', label: '۱۲ تا ۱۴', start: 12},
            {value: '14-16', label: '۱۴ تا ۱۶', start: 14},
        ];

        dropdown.innerHTML = '';

        const available = isToday
            ? slots.filter(slot => currentHour < slot.start)
            : slots;

        if (available.length === 0) {
            dropdown.innerHTML = '<div class="custom-select-option" style="color:#94a3b8;">امروز ساعتی موجود نیست</div>';
            return;
        }

        available.forEach(slot => {
            const div = document.createElement('div');
            div.className = 'custom-select-option';
            div.textContent = slot.label;
            div.onclick = function () {
                selectOption('time-select', 'delivery-time', slot.value);
                document.querySelector('#time-select .custom-select-value').textContent = slot.label;
            };
            dropdown.appendChild(div);
        });
    }

    function buildConfirmTimeOptions(selectedDay = null) {
        const dropdown = document.getElementById('confirm-time-dropdown');
        if (!dropdown) return;

        const currentHour = new Date().getHours();

        const days = ['یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه'];
        const todayName = days[new Date().getDay()];
        const isToday = selectedDay === todayName || selectedDay === null;

        const slots = [
            {value: '10-12', label: '۱۰ تا ۱۲', start: 10},
            {value: '12-14', label: '۱۲ تا ۱۴', start: 12},
            {value: '14-16', label: '۱۴ تا ۱۶', start: 14},
        ];

        dropdown.innerHTML = '';

        const available = isToday
            ? slots.filter(slot => currentHour < slot.start)
            : slots;

        if (available.length === 0) {
            dropdown.innerHTML = '<div class="confirm-custom-option" style="color:#94a3b8;">امروز ساعتی موجود نیست</div>';
            return;
        }

        available.forEach(slot => {
            const div = document.createElement('div');
            div.className = 'confirm-custom-option';
            div.textContent = slot.label;
            div.onclick = function () {
                document.getElementById('confirm-delivery-time').value = slot.value;
                document.getElementById('confirm-delivery-time-text').textContent = slot.label;
                document.getElementById('confirm-time-wrap').classList.remove('open');
            };
            dropdown.appendChild(div);
        });
    }

    function toggleConfirmSelectEdit(wrapId, triggerId) {
        const trigger = document.getElementById(triggerId);
        const iconWrap = document.getElementById('icon-edit-' + wrapId);
        const wrap = document.getElementById(wrapId);
        const isEditing = !trigger.classList.contains('disabled-trigger');

        if (isEditing) {
            trigger.classList.add('disabled-trigger');
            trigger.classList.remove('editing'); // اضافه کن
            trigger.onclick = null;
            wrap.classList.remove('open');
            if (iconWrap) iconWrap.innerHTML = '<img src="{{ asset("icon/Edit2-TwoTone-32px.svg") }}" width="20" height="20">';
        } else {
            trigger.classList.remove('disabled-trigger');
            trigger.classList.add('editing'); // اضافه کن
            trigger.onclick = function () {
                const isOpen = wrap.classList.contains('open');
                document.querySelectorAll('.confirm-custom-select.open').forEach(s => s.classList.remove('open'));
                if (!isOpen) wrap.classList.add('open');
            };
            if (iconWrap) iconWrap.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        }
    }

    function selectConfirmOption(wrapId, inputId, value, label) {
        const wrap = document.getElementById(wrapId);
        const input = document.getElementById(inputId);
        const textEl = document.getElementById(inputId + '-text');

        input.value = value;
        if (textEl) textEl.textContent = label;

        wrap.querySelectorAll('.confirm-custom-option').forEach(opt => {
            opt.classList.toggle('selected', opt.textContent.trim() === label);
        });
        wrap.classList.remove('open');

        if (inputId === 'confirm-delivery') {
            selectedDelivery = value;
            toggleConfirmDeliveryFields();
        }

        if (inputId === 'confirm-delivery-day') {
            buildConfirmTimeOptions(value);
            document.getElementById('confirm-delivery-time').value = '';
            document.getElementById('confirm-delivery-time-text').textContent = 'انتخاب ساعت';
        }

        calculateTotalInvoice();
    }

    function getAvailableDays() {
        const days = ['یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه'];
        const todayIndex = new Date().getDay();
        const available = [];
        for (let i = 0; i < 7; i++) {
            available.push(days[(todayIndex + i) % 7]);
        }
        return available;
    }

    function buildDayOptions() {
        const dropdown = document.getElementById('day-dropdown');

        if (!dropdown) return;

        dropdown.innerHTML = '';
        getAvailableDays().forEach(day => {
            const div = document.createElement('div');
            div.className = 'custom-select-option';
            div.textContent = day;
            div.onclick = function () {
                selectOption('day-select', 'delivery-day', day);
                document.querySelector('#day-select .custom-select-value').textContent = day;
                buildTimeOptions(day);
                document.getElementById('delivery-time').value = '';
                document.querySelector('#time-select .custom-select-value').textContent = 'انتخاب ساعت';
            };
            dropdown.appendChild(div);
        });
    }

    function buildConfirmDayOptions() {
        const dropdown = document.getElementById('confirm-day-dropdown');
        if (!dropdown) return;

        dropdown.innerHTML = '';
        getAvailableDays().forEach(day => {
            const div = document.createElement('div');
            div.className = 'confirm-custom-option';
            div.textContent = day;
            div.onclick = function () {
                selectConfirmOption('confirm-day-wrap', 'confirm-delivery-day', day, day);
            };
            dropdown.appendChild(div);
        });
    }

    function createPreview(file) {

        const reader = new FileReader();

        reader.onload = function (e) {

            const div = document.createElement('div');

            div.className = 'preview-item';

            div.style.opacity = '0';
            div.style.transform = 'translateY(15px) scale(.95)';
            div.style.transition = '.35s ease';

            div.innerHTML = `
                <img src="${e.target.result}" alt="">
                <button type="button" class="remove-btn">✕</button>
            `;

            previewGrid.appendChild(div);

            setTimeout(() => {
                div.style.opacity = '1';
                div.style.transform = 'translateY(0) scale(1)';
            }, 50);

            // حذف عکس
            div.querySelector('.remove-btn').addEventListener('click', () => {

                div.style.opacity = '0';
                div.style.transform = 'scale(.8)';

                setTimeout(() => {
                    div.remove();
                }, 250);

                selectedFiles = selectedFiles.filter(f => f !== file);

                updateInputFiles();
            });

        };

        reader.readAsDataURL(file);
    }

    // آپدیت input واقعی
    function updateInputFiles() {

        const dataTransfer = new DataTransfer();

        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });

        imageInput.files = dataTransfer.files;
    }

    function prevStep() {
        const config = stepStructure[currentStep];
        if (config.currentSub > 1) {
            config.currentSub--;
            updateFormUI();
            window.scrollTo({top: 0, behavior: 'smooth'});
        } else {
            if (currentStep > 1) {
                currentStep--;
                stepStructure[currentStep].currentSub = stepStructure[currentStep].subSteps;
                updateFormUI();
                window.scrollTo({top: 0, behavior: 'smooth'});
            }
        }
    }

    function showErrorToast(msg) {
        const toast = document.getElementById('toast');
        document.getElementById('toast-msg').textContent = msg;
        toast.className = 'toast-container toast-error show';
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => closeToast(), 3500);
    }


    function goToStep(step) {

        // اگر روی همون استپ کلیک شد
        if (step === currentStep) return;

        // فقط اجازه رفتن به یک مرحله جلوتر
        if (step > currentStep + 1) {

            showErrorToast('ابتدا مراحل قبلی را تکمیل کنید');

            return;
        }

        // ولیدیشن استپ 2
        if (currentStep === 2 && step > 2) {

            if (!validateStep2()) return;
        }

        // ولیدیشن استپ 3
        if (currentStep === 3 && step > 3) {

            if (!validateStep3()) return;
        }

        currentStep = step;

        stepStructure[currentStep].currentSub = 1;

        if (currentStep === 4 || currentStep === 5) {
            fillConfirmData();
        }

        updateFormUI();

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function addAddonAndNext(id) {
        let sub = subProducts.find(p => p.id === id);
        if (sub && sub.qty === 0) {
            sub.qty = 1;
            document.getElementById(`sub-qty-${id}`).value = 1;
        }
        nextStep();
    }

    function toggleDropdown(id) {
        const select = document.getElementById(id);
        const isOpen = select.classList.contains('open');
        // بستن همه dropdown های باز
        document.querySelectorAll('.custom-select.open').forEach(s => s.classList.remove('open'));
        if (!isOpen) select.classList.add('open');
    }

    function selectOption(selectId, inputId, value) {
        const select = document.getElementById(selectId);
        const input = document.getElementById(inputId);
        const trigger = select.querySelector('.custom-select-value');

        trigger.textContent = value || (inputId === 'delivery-day' ? 'انتخاب روز' : 'انتخاب ساعت');
        input.value = value;

        select.querySelectorAll('.custom-select-option').forEach(opt => {
            opt.classList.toggle('selected', opt.textContent.trim() === trigger.textContent.trim());
        });

        select.classList.remove('open');

        // اگه روز انتخاب شد، ساعت‌ها رو rebuild کن
        if (inputId === 'delivery-day') {
            buildTimeOptions(value);
            // ریست ساعت
            document.getElementById('delivery-time').value = '';
            document.querySelector('#time-select .custom-select-value').textContent = 'انتخاب ساعت';
        }
    }

    // بستن dropdown با کلیک خارج
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-select')) {
            document.querySelectorAll('.custom-select.open').forEach(s => s.classList.remove('open'));
        }
        if (!e.target.closest('.confirm-custom-select')) {
            document.querySelectorAll('.confirm-custom-select.open').forEach(s => s.classList.remove('open'));
        }
    });

    function validateStep2() {
        let isValid = true;
        const validations = [
            {field: 'first-name', error: 'first-name-error'},
            {field: 'last-name', error: 'last-name-error'},
            {field: 'mobile', error: 'mobile-error'},
            {field: 'province', error: 'province-error'},
            {field: 'city', error: 'city-error'},
            {field: 'address', error: 'address-error'}
        ];
        validations.forEach(item => {
            const field = document.getElementById(item.field);
            const error = document.getElementById(item.error);
            field.classList.remove('input-error');
            error.style.display = 'none';
            if (!field.value.trim()) {
                field.classList.add('input-error');
                error.style.display = 'flex';
                isValid = false;
            }
        });
        const mobile = document.getElementById('mobile');
        const mobileError = document.getElementById('mobile-error');

        // تبدیل اعداد فارسی/عربی به انگلیسی
        const normalizedMobile = mobile.value.trim()
            .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
            .replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));

        // مقدار input رو هم آپدیت کن
        mobile.value = normalizedMobile;

        if (normalizedMobile && !/^09\d{9}$/.test(normalizedMobile)) {
            mobile.classList.add('input-error');
            mobileError.textContent = 'شماره تماس معتبر نیست';
            mobileError.style.display = 'block';
            isValid = false;
        }

        return isValid;
    }

    function validateStep3() {
        if (selectedDelivery === 'express') {
            const day = document.getElementById('delivery-day').value;
            const time = document.getElementById('delivery-time').value;
            const error = document.getElementById('delivery-error');
            error.style.display = 'none';

            let isValid = true;

            if (!day) {
                document.querySelector('#day-select .custom-select-trigger').style.borderColor = '#ef4444';
                isValid = false;
            }

            if (!time) {
                document.querySelector('#time-select .custom-select-trigger').style.borderColor = '#ef4444';
                isValid = false;
            }

            if (!isValid) {
                error.style.display = 'block';
                return false;
            }

            document.querySelector('#day-select .custom-select-trigger').style.borderColor = '';
            document.querySelector('#time-select .custom-select-trigger').style.borderColor = '';
        }
        return true;
    }

    function enableInlineEdit(id) {
        if (event) event.preventDefault();
        const field = document.getElementById(id);
        const iconWrap = document.getElementById('icon-edit-' + id);

        if (!field.hasAttribute('readonly')) {
            // چک شماره موبایل
            if (id === 'confirm-mobile') {
                const mobile = field.value.trim();
                if (mobile && !/^09\d{9}$/.test(mobile)) {
                    field.value = field.dataset.prevValue || '';
                    showErrorToast('شماره موبایل باید با 09 شروع شده و ۱۱ رقم باشد');
                    field.setAttribute('readonly', true);
                    field.classList.remove('editing');
                    if (iconWrap) iconWrap.innerHTML = '<img src="{{ asset("icon/Edit2-TwoTone-32px.svg") }}" width="20" height="20">';
                    return;
                }
            }

            if (!field.value.trim()) {
                field.value = field.dataset.prevValue || '';
            }
            field.setAttribute('readonly', true);
            field.classList.remove('editing');
            if (iconWrap) iconWrap.innerHTML = '<img src="{{ asset("icon/Edit2-TwoTone-32px.svg") }}" width="20" height="20">';
            syncConfirmToOriginals();
            return;
        }

        field.dataset.prevValue = field.value;
        field.removeAttribute('readonly');
        field.classList.add('editing');
        field.focus();
        if (iconWrap) iconWrap.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    }

    function enableInlineSelect(id) {
        const field = document.getElementById(id);
        const iconWrap = document.getElementById('icon-edit-' + id);

        if (!field.disabled) {
            // ذخیره و بستن
            field.disabled = true;
            field.classList.remove('editing');
            if (iconWrap) iconWrap.innerHTML = '<img src="{{ asset("icon/Edit2-TwoTone-32px.svg") }}" width="20" height="20">';
            if (id === 'confirm-delivery') {
                selectedDelivery = field.value;
                toggleConfirmDeliveryFields();
            }
            calculateTotalInvoice();
            return;
        }

        // باز کردن ادیت
        field.disabled = false;
        field.classList.add('editing');
        field.focus();
        if (iconWrap) iconWrap.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    }

    function manualSubQty(id, value) {
        let sub = subProducts.find(p => p.id === id);
        if (sub) {
            let qty = parseInt(value);
            if (isNaN(qty) || qty < 0) qty = 0;
            sub.qty = qty;
            syncSubQtyInputs(id, qty);
            calculateTotalInvoice();
        }
    }

    function syncConfirmToOriginals() {
        const fullname = document.getElementById('confirm-fullname').value.trim().split(' ');
        document.getElementById('first-name').value = fullname[0] || '';
        document.getElementById('last-name').value = fullname.slice(1).join(' ') || '';
        document.getElementById('mobile').value = document.getElementById('confirm-mobile').value;
        calculateTotalInvoice();
    }

    function closeToast() {
        const toast = document.getElementById('toast');
        toast.classList.remove('show');
    }

    let toastTimeout;

    function showSuccessToast(msg) {
        const toast = document.getElementById('toast');
        document.getElementById('toast-msg').textContent = msg;
        toast.className = 'toast-container toast-success show';
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => closeToast(), 3500);
    }

    function copyCardNumber(text) {
        let englishText = text.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
        navigator.clipboard.writeText(englishText)
            .then(() => {
                showSuccessToast('شماره کارت با موفقیت کپی شد');
            })
            .catch(() => {
                showSuccessToast('خطا در کپی شماره کارت');
            });
    }

    function handleFileSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileList = document.getElementById('files-list');
            const fileItem = document.createElement('div');
            fileItem.className = 'file-progress-item';
            fileItem.innerHTML = `
                <div class="file-info">
                    <div class="file-icon">📄</div>
                    <div class="file-details">
                        <div class="file-name">${file.name}</div>
                        <div class="file-status">در حال بارگذاری...</div>
                    </div>
                </div>
                <div class="file-actions"><div class="spinner"></div></div>
            `;
            fileList.appendChild(fileItem);
            setTimeout(() => {
                fileItem.querySelector('.file-actions').innerHTML = '✅';
                fileItem.querySelector('.file-status').textContent = 'بارگذاری شد';
            }, 1500);
        }
    }

    // اجرای اولیه
    updateFormUI();

    // Start PWA
    (function () {
        let deferredPrompt;
        const banner = document.getElementById('pwa-install-banner');
        if (!banner) return;

        const installBtn = document.getElementById('pwa-install-btn');
        const closeBtn = document.getElementById('pwa-close-btn');

        const today = new Date().toDateString();

        // اگه قبلاً نصب کرده یا امروز رد کرده، نشون نده
        if (localStorage.getItem('pwa-installed') === '1' ||
            localStorage.getItem('pwa-banner-dismissed') === today) {
            banner.remove();
            return;
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            setTimeout(() => {
                banner.classList.add('show');
            }, 3000);
        });

        installBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const {outcome} = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                localStorage.setItem('pwa-installed', '1');
            }
            hideBanner();
            deferredPrompt = null;
        });

        closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            hideBanner();
            localStorage.setItem('pwa-banner-dismissed', today);
        });

        window.addEventListener('appinstalled', () => {
            hideBanner();
            localStorage.setItem('pwa-installed', '1');
        });

        function hideBanner() {
            banner.style.bottom = '-150px';
            setTimeout(() => banner.remove(), 500);
        }
    })();
    // // End PWA

</script>
</body>

</html>
