<?php

namespace App\Enum;

use App\Enum\Traits\EnumToArray;

enum Currency: string
{
    use EnumToArray;

    case ALL = 'all';
    case AFN = 'afn';
    case ARS = 'ars';
    case AWG = 'awg';
    case AUD = 'aud';
    case AZN = 'azn';
    case BSD = 'bsd';
    case BBD = 'bbd';
    case BDT = 'bdt';
    case BYR = 'byr';
    case BZD = 'bzd';
    case BMD = 'bmd';
    case BOB = 'bob';
    case BAM = 'bam';
    case BWP = 'bwp';
    case BGN = 'bgn';
    case BRL = 'brl';
    case BND = 'bnd';
    case KHR = 'khr';
    case CAD = 'cad';
    case KYD = 'kyd';
    case CLP = 'clp';
    case CNY = 'cny';
    case COP = 'cop';
    case CRC = 'crc';
    case HRK = 'hrk';
    case CUP = 'cup';
    case CZK = 'czk';
    case DKK = 'dkk';
    case DOP = 'dop';
    case XCD = 'xcd';
    case EGP = 'egp';
    case SVC = 'svc';
    case EEK = 'eek';
    case EUR = 'eur';
    case FKP = 'fkp';
    case FJD = 'fjd';
    case GHC = 'ghc';
    case GIP = 'gip';
    case GTQ = 'gtq';
    case GGP = 'ggp';
    case GYD = 'gyd';
    case HNL = 'hnl';
    case HKD = 'hkd';
    case HUF = 'huf';
    case ISK = 'isk';
    case INR = 'inr';
    case IDR = 'idr';
    case IRR = 'irr';
    case IMP = 'imp';
    case ILS = 'ils';
    case JMD = 'jmd';
    case JPY = 'jpy';
    case JEP = 'jep';
    case KZT = 'kzt';
    case KPW = 'kpw';
    case KRW = 'krw';
    case KGS = 'kgs';
    case LAK = 'lak';
    case LVL = 'lvl';
    case LBP = 'lbp';
    case LRD = 'lrd';
    case LTL = 'ltl';
    case MKD = 'mkd';
    case MYR = 'myr';
    case MUR = 'mur';
    case MXN = 'mxn';
    case MNT = 'mnt';
    case MZN = 'mzn';
    case NAD = 'nad';
    case NPR = 'npr';
    case ANG = 'ang';
    case NZD = 'nzd';
    case NIO = 'nio';
    case NGN = 'ngn';
    case NOK = 'nok';
    case OMR = 'omr';
    case PKR = 'pkr';
    case PAB = 'pab';
    case PYG = 'pyg';
    case PEN = 'pen';
    case PHP = 'php';
    case PLN = 'pln';
    case QAR = 'qar';
    case RON = 'ron';
    case RUB = 'rub';
    case SHP = 'shp';
    case SAR = 'sar';
    case RSD = 'rsd';
    case SCR = 'scr';
    case SGD = 'sgd';
    case SBD = 'sbd';
    case SOS = 'sos';
    case ZAR = 'zar';
    case LKR = 'lkr';
    case SEK = 'sek';
    case CHF = 'chf';
    case SRD = 'srd';
    case SYP = 'syp';
    case TWD = 'twd';
    case THB = 'thb';
    case TTD = 'ttd';
    case TRY = 'try';
    case TRL = 'trl';
    case TVD = 'tvd';
    case UAH = 'uah';
    case GBP = 'gbp';
    case USD = 'usd';
    case UYU = 'uyu';
    case UZS = 'uzs';
    case VEF = 'vef';
    case VND = 'vnd';
    case YER = 'yer';
    case ZWD = 'zwd';
}