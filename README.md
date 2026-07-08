# 🏢 UiTM Kampus Kuala Terengganu Hostel Booking System

An automated, data-driven web application built with **Laravel** and **Tailwind CSS** designed to streamline room allocation management for Kolej Kasa (Male) and Kolej Sutera (Female). The system features intelligent dynamic gender-cohort filtering, comprehensive administrative bulk reservation tools, automated policy violation tracking, and integrated real-time notification feeds.

---

## 👥 Group Project Team Members (CS270 - Semester 4)
* **AHMAD IRFAN BIN MUHAMAD ARIF** (2024669856)
* **MOHAMAD IZZRUL EMIR BIN MD SHAFIAN** (2024236368)
* **HARZAN QAYYUM BIN MAIZAN** (2024690002)
* **MEOR MUHAMMAD SYARIFF BIN MEOR SHAHAROL NIZAM** (2024680092)

---

## 🚀 Key System Features

### 🏢 Student Portal Workflows
* **Intelligent Inventory Matcher:** Dynamically filters room availability matching the authenticated student's true `gender` attribute—completely separating male (**KXXX**) and female (**SXXX**) allocations.
* **Dual Booking Modes:** Accommodates single reservation requirements (`SOLO`) alongside integrated multi-occupant (`GROUP`) tracking logic.
* **Dynamic Compliance Shield:** Directly implements 3-strike disciplinary guardrails. Students with 3 or more policy violation records are automatically locked out of bedroom booking screens and redirected to their restriction ledger.
* **Integrated Bulletin Board:** Displays real-time, priority-sorted administrative announcements directly on the main viewport dashboard.

### 🛡️ JPK Administrative Suite
* **Dynamic Header Layouts:** Session-driven navigation bars tracking individual admin identities (`ADMIN001`, `ADMIN002`, `ADMIN003`) across all control panel interfaces.
* **Bulk Footprint Controllers:** Powerful administrative override deck enabling JPK supervisors to block or release entire floor levels or custom selections instantly.
* **Audited Pass Revocations:** Active booking cancellations are supplemented with a required text reason log string for administrative transparency.
* **Urgent Bulletin Broadcaster:** Allows administrators to publish, prioritize (`is_urgent`), or permanently expunge official notifications.

---

## 🛠️ Core Technology Stack
* **Framework:** Laravel 10 / 11
* **Frontend UI Engine:** Tailwind CSS via CDN integration
* **Database Engine:** MySQL / MariaDB (via Eloquent Query Builder and Transaction Facades)
* **Session Handler:** Native PHP Session Pool management

---

## ⚙️ Installation & Workspace Setup

### 1. Repository Setup
Clone the project repository to your local directory workspace and navigate into it:
```bash
git clone https://github.com/irfan-view/CSC577-Hostel-Booking.git
cd CSC577-Hostel-Booking-main