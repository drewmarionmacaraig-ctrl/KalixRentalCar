<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Rental Agreement — {{ $booking->booking_code }}</title>
    <style>
        @page { margin: 0.5in 0.55in; }
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            line-height: 1.38;
            color: #111827;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .brand-title {
            color: #B91C1C;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .brand-sub {
            color: #B91C1C;
            font-size: 8.5pt;
            margin-top: 2px;
        }
        .doc-title {
            color: #B91C1C;
            font-size: 11pt;
            font-weight: bold;
            text-align: left;
            margin-top: 8px;
            margin-bottom: 4px;
        }
        .section-title {
            color: #B91C1C;
            font-size: 10pt;
            font-weight: bold;
            margin-top: 9px;
            margin-bottom: 4px;
        }
        table.details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        table.details td {
            width: 50%;
            vertical-align: top;
            padding: 3px 10px 4px 0;
        }
        .field-label {
            font-weight: bold;
            font-size: 8.5pt;
            color: #111827;
        }
        .field-value {
            border-bottom: 1px solid #374151;
            padding: 2px 0 3px 0;
            font-size: 9pt;
            min-height: 14px;
        }
        ul.clauses {
            margin: 2px 0 4px 16px;
            padding: 0;
        }
        ul.clauses li {
            margin-bottom: 3px;
        }
        .page-break {
            page-break-before: always;
        }
        table.checklist {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 8.8pt;
        }
        table.checklist th {
            background-color: #E5E7EB;
            color: #B91C1C;
            text-align: left;
            padding: 5px 8px;
            border: 1px solid #9CA3AF;
        }
        table.checklist td {
            padding: 6px 8px;
            border: 1px solid #9CA3AF;
        }
        table.sigs {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        table.sigs td {
            width: 50%;
            vertical-align: top;
            padding-right: 18px;
        }
        .sig-line {
            border-bottom: 1px solid #111827;
            height: 22px;
            margin-bottom: 4px;
            font-weight: bold;
            font-size: 9pt;
            padding-top: 6px;
        }
        .damage-box {
            border-top: 1.5px solid #B91C1C;
            margin-top: 14px;
            padding-top: 8px;
        }
        .footer {
            position: fixed;
            bottom: -0.3in;
            left: 0;
            right: 0;
            border-top: 1px solid #B91C1C;
            padding-top: 3px;
            font-size: 7.5pt;
            color: #6B7280;
        }
    </style>
</head>
<body>
    <div class="footer">
        KLX Affordable Cars for Rent – Palawan | Booking Ref: {{ $booking->booking_code }}
    </div>

    {{-- PAGE 1 --}}
    <div class="header">
        <div class="brand-title">KLX AFFORDABLE CARS FOR RENT – PALAWAN</div>
        <div class="brand-sub">Drive More, Explore More | Foton Tagburos, Puerto Princesa | SMS / Viber / WhatsApp: 0907-883-8861</div>
    </div>

    <div class="doc-title">CAR RENTAL AGREEMENT</div>
    <p style="margin: 0 0 6px 0;">
        This agreement is made between KLX Affordable Cars for Rent – Palawan ("Lessor") and the undersigned renter ("Renter").
        <strong>Booking Ref: {{ $booking->booking_code }}</strong>
    </p>

    <div class="section-title">1. RENTER &amp; VEHICLE DETAILS</div>
    <table class="details">
        <tr>
            <td>
                <div class="field-label">Renter Name</div>
                <div class="field-value">{{ $booking->renter_name }}</div>
            </td>
            <td>
                <div class="field-label">Contact No.</div>
                <div class="field-value">{{ $booking->contact_no }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field-label">Driver's License No.</div>
                <div class="field-value">{{ $booking->drivers_license_no }}</div>
            </td>
            <td>
                <div class="field-label">Government ID No.</div>
                <div class="field-value">{{ $booking->government_id_no }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field-label">Vehicle / Unit</div>
                <div class="field-value">{{ $booking->vehicle->name }} ({{ $booking->vehicle->year }})</div>
            </td>
            <td>
                <div class="field-label">Plate / Conduction No.</div>
                <div class="field-value">{{ $booking->vehicle->plate_number }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field-label">Rental Start Date &amp; Time</div>
                <div class="field-value">{{ $booking->pickup_datetime->format('M d, Y g:i A') }}</div>
            </td>
            <td>
                <div class="field-label">Return Date &amp; Time</div>
                <div class="field-value">{{ $booking->return_datetime->format('M d, Y g:i A') }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field-label">Pickup Location</div>
                <div class="field-value">{{ $booking->pickup_location }}</div>
            </td>
            <td>
                <div class="field-label">Return / Delivery Location</div>
                <div class="field-value">{{ $booking->return_location }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field-label">Route / Intended Use</div>
                <div class="field-value">{{ $booking->route_intended_use ?? 'Puerto Princesa / Palawan' }}</div>
            </td>
            <td>
                <div class="field-label">Total Rental Rate ({{ $booking->total_days }} day/s)</div>
                <div class="field-value">PHP {{ number_format((float) $booking->total_amount, 2) }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">2. PAYMENT &amp; RESERVATION</div>
    <ul class="clauses">
        <li>Reservation is confirmed only after the agreed down payment is received and acknowledged by KLX. The down payment is NON-REFUNDABLE, subject to agreed terms and applicable law.</li>
        <li>Remaining balance must be paid on or before the agreed rental schedule. Accepted methods may include GCash, PalawanPay, bank transfer, and credit card; credit card transactions carry a 6% bank/processing charge.</li>
    </ul>

    <div class="section-title">3. VEHICLE CONDITION &amp; DELIVERY</div>
    <ul class="clauses">
        <li>Renter must inspect the vehicle upon release and document existing scratches, dents, damage, fuel level, mileage, and accessories before use. Return the vehicle in substantially the same condition, subject to normal wear and tear.</li>
        <li>Delivery/pickup fees are separate from the rental rate unless expressly included in the booking.</li>
    </ul>

    <div class="section-title">4. RENTER RESPONSIBILITIES</div>
    <ul class="clauses">
        <li>Use the vehicle with reasonable care, lawfully, and within the agreed route/use. Do not sub-rent, lend, sell, alter, or allow an unauthorized person to operate it.</li>
        <li>Renter is responsible, subject to this agreement and applicable law, for damage, loss, expense, traffic violations, parking, tolls, impound fees, and other renter-caused charges during the rental period.</li>
        <li>Report accidents, damage, breakdowns, or other issues to KLX immediately.</li>
    </ul>

    <div class="section-title">5. DAMAGE, LOSS &amp; ACCIDENTS</div>
    <ul class="clauses">
        <li>Report new damage or loss immediately. Charges may be based on actual repair, replacement, recovery, or other documented cost, subject to applicable law.</li>
        <li>In an accident, prioritize safety, notify authorities when required, and contact KLX as soon as reasonably possible. Do not admit liability, abandon the vehicle, or arrange major repairs without contacting KLX first, except when emergency action is reasonably necessary for safety.</li>
    </ul>

    <div class="section-title">6. RETURN, EXTENSION &amp; LATE RETURN</div>
    <ul class="clauses">
        <li>Return the vehicle at the agreed date, time, and location. Extensions require KLX approval before the original return time and may incur additional charges subject to availability. Late return may result in charges under the agreed rental terms.</li>
    </ul>

    <div class="section-title">7. FUEL, CLEANLINESS &amp; FEES</div>
    <ul class="clauses">
        <li>Return the vehicle at the agreed fuel level and cleanliness condition. Carwash, delivery/pickup, fuel, toll, parking, and other agreed charges are payable under the booking terms. Additional charges should be explained and, where practicable, supported by reasonable documentation.</li>
    </ul>

    {{-- PAGE 2 --}}
    <div class="page-break"></div>

    <div class="section-title" style="margin-top: 0;">8. AUTHORIZED DRIVER</div>
    <table class="details">
        <tr>
            <td>
                <div class="field-label">Primary Driver Name</div>
                <div class="field-value">{{ $booking->primary_driver_name }}</div>
            </td>
            <td>
                <div class="field-label">Driver's License No.</div>
                <div class="field-value">{{ $booking->primary_driver_license }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field-label">Additional Authorized Driver</div>
                <div class="field-value">{{ $booking->additional_driver_name ?? 'N/A' }}</div>
            </td>
            <td>
                <div class="field-label">Driver's License No.</div>
                <div class="field-value">{{ $booking->additional_driver_license ?? 'N/A' }}</div>
            </td>
        </tr>
    </table>
    <p style="margin: 2px 0 6px 0;">
        Only drivers disclosed to and accepted by KLX may operate the vehicle. Renter remains responsible for the vehicle and acts or omissions of any authorized driver, subject to applicable law.
    </p>

    <div class="section-title">9. CANCELLATION / CHANGES</div>
    <ul class="clauses">
        <li>Changes to dates, vehicle/unit, pickup location, or rental duration are subject to availability and KLX approval. The non-refundable down payment applies under booking terms communicated to the Renter, except where applicable law requires otherwise.</li>
    </ul>

    <div class="section-title">10. ACKNOWLEDGMENT &amp; SIGNATURES</div>
    <p style="margin: 2px 0 4px 0;">
        By signing below, Renter confirms the information is accurate, the terms have been explained, and Renter had an opportunity to ask questions before signing.
    </p>
    <table class="sigs">
        <tr>
            <td>
                <div class="field-label">RENTER SIGNATURE</div>
                <div class="sig-line">{{ $booking->contract_signature_name }}</div>
                <div style="font-size: 8.5pt;">Printed Name: {{ $booking->contract_signature_name ?? $booking->renter_name }}</div>
                <div style="font-size: 8.5pt;">Date &amp; Time: {{ $booking->contract_accepted_at?->format('M d, Y g:i A') ?? '' }}</div>
            </td>
            <td>
                <div class="field-label">KLX / LESSOR REPRESENTATIVE</div>
                <div class="sig-line"></div>
                <div style="font-size: 8.5pt;">Printed Name: ______________________________</div>
                <div style="font-size: 8.5pt;">Date &amp; Time: ______________________________</div>
            </td>
        </tr>
    </table>

    <div class="section-title" style="margin-top: 14px;">VEHICLE RELEASE / RETURN CHECKLIST</div>
    @php
        $rel = $booking->release_checklist ?? [];
        $ret = $booking->return_checklist ?? [];
    @endphp
    <table class="checklist">
        <thead>
            <tr>
                <th style="width: 34%;">ITEM</th>
                <th style="width: 33%;">RELEASE</th>
                <th style="width: 33%;">RETURN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Exterior / Body</td>
                <td>{{ $rel['exterior'] ?? '' }}</td>
                <td>{{ $ret['exterior'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Interior</td>
                <td>{{ $rel['interior'] ?? '' }}</td>
                <td>{{ $ret['interior'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Tires / Wheels</td>
                <td>{{ $rel['tires'] ?? '' }}</td>
                <td>{{ $ret['tires'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Lights / Signals</td>
                <td>{{ $rel['lights'] ?? '' }}</td>
                <td>{{ $ret['lights'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Fuel Level</td>
                <td>{{ $rel['fuel'] ?? '' }}</td>
                <td>{{ $ret['fuel'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Accessories / Documents</td>
                <td>{{ $rel['accessories'] ?? '' }}</td>
                <td>{{ $ret['accessories'] ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="damage-box">
        <div class="section-title" style="margin-top: 0;">DAMAGE, REPAIR COST &amp; DAILY RENTAL CHARGE</div>
        <p style="font-size: 8.5pt; margin: 2px 0 8px 0;">
            If damage results from the renter's negligence, misuse, reckless driving, unauthorized use, or breach of this agreement, the renter must pay the actual repair or replacement cost and reasonable related towing, parts, labor, assessment, and repair costs. The renter must also pay the agreed daily rental rate for each day the vehicle is unavailable for rent during repairs, subject to applicable law. This clause forms part of this agreement. By signing below, the renter acknowledges and agrees to this term.
        </p>
        <table class="sigs" style="margin-top: 6px;">
            <tr>
                <td>
                    <div class="field-label">RENTER SIGNATURE:</div>
                    <div class="sig-line">{{ $booking->contract_signature_name }}</div>
                    <div style="font-size: 8.5pt;">Printed Name: {{ $booking->contract_signature_name ?? $booking->renter_name }}</div>
                    <div style="font-size: 8.5pt;">Date &amp; Time: {{ $booking->contract_accepted_at?->format('M d, Y g:i A') ?? '' }}</div>
                </td>
                <td>
                    <div class="field-label">KLX / LESSOR REPRESENTATIVE:</div>
                    <div class="sig-line"></div>
                    <div style="font-size: 8.5pt;">Printed Name: ______________________________</div>
                    <div style="font-size: 8.5pt;">Date &amp; Time: ______________________________</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
