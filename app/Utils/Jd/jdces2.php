<?php

include 'util/RequestUtils.php';
include 'util/SignEnvelope.php';
include 'util/SignUtils.php';
include 'util/HttpUtils.php';
include 'util/TradeStatusUtils.php';
include 'common/Constants.php';
include 'CreditPayment.php';
include 'CreditPayment2.php';

class jdces2
{

    public function execute()
    {
        //main::agreementSign();
        //main::agreementSignConfirm();
        //main::agreementPay();
        //main::queryTrade();
//		main::asyVerifySign();
    }

    /**
     * 1.1 签约请求接口
     */
    public function agreementSign($idNo, $idName, $phone, $cardNo, $bankCode)
    {
        $res = CreditPayment::agreementSign($idNo, $idName, $phone, $cardNo, $bankCode);
        return $res;
    }

    /**
     * 1.2 签约确认接口
     */
    public function agreementSignConfirm($outTradeNo, $agreementNo, $code)
    {
        $res = CreditPayment::agreementSignConfirm($outTradeNo, $agreementNo, $code);
        return $res;
    }

    /**
     * 1.3交易接口
     */
    public function agreementPay($agreementNo, $outTradeNo, $tradeAmount, $order, $callback = 'payCallBack')
    {
        return CreditPayment::agreementPay($agreementNo, $outTradeNo, $tradeAmount, $order, $callback);
    }

    /**
     * 2 交易查询
     */
    public function queryTrade()
    {
        CreditPayment::queryTrade();
    }

    /**
     * 3 异步通知验签
     */
    public function asyVerifySign($content)
    {
        //$returnContent = "{\"charset\":\"UTF-8\",\"data\":\"eyJjb2RlIjoiOTk5OTkiLCJkZXNjIjoi5Lqk5piT5oiQ5YqfIiwiZW5jcnlwdFR5cGUiOiJBUDciLCJzaWduVHlwZSI6IlNIQS0yNTYiLCJ0cmFkZUluZm8iOnsiYml6VHJhZGVObyI6IjViOTVkODNmOThlN2QiLCJjcmVhdGVkRGF0ZSI6MTUzNjU0Njg4NTAwMCwiaW5DdXN0b21lckluZm8iOnsiY3VzdG9tZXJObyI6IjM2MDA4MDAwMDIyMjk0NTMxNCJ9LCJvdXRDdXN0b21lckluZm8iOnt9LCJvdXRUcmFkZU5vIjoiNWI5NWQ4M2Y5OGU3YSIsInNoYXJlSW5mb1Jlc3VsdExpc3QiOlt7ImJhbmtBY2NvdW50SW5mbyI6eyJiYW5rQWNjb3VudE5hbWUiOiIq5rC456eRIiwiYmFua0FjY291bnRObyI6IjM3NzgiLCJiYW5rQWNjb3VudFR5cGUiOiJQIiwiYmFua0NvZGUiOiJCQ00iLCJjYXJkVHlwZSI6IkRFIn0sImZhaWxDb2RlIjoiOTk5OTkiLCJmYWlsUmVhc29uIjoiIiwiZmluaXNoRGF0ZSI6IjIwMTgtMDktMTAgMTA6Mzg6NTIiLCJwYXlUb29sIjoiVFJBTiIsInRyYWRlQW1vdW50Ijp7ImFtb3VudCI6MSwiYmlnRGVjaW1hbFl1YW4iOjAuMDEsImN1cnJlbmN5Q29kZSI6IkNOWSIsInN0cmluZ1l1YW4iOiIwLjAxIn0sInRyYWRlU3RhdHVzIjoiRklOSSIsInRyYWRlVHlwZSI6IlRfQUdEIn0seyJiYW5rQWNjb3VudEluZm8iOnsiYmFua0FjY291bnROYW1lIjoiKuawuOenkSIsImJhbmtBY2NvdW50Tm8iOiI2MTAzIiwiYmFua0FjY291bnRUeXBlIjoiUCIsImJhbmtDb2RlIjoiQ01CIiwiY2FyZFR5cGUiOiJDUiJ9LCJmYWlsQ29kZSI6IjAwMDAiLCJmYWlsUmVhc29uIjoi5oiQ5YqfIiwiZmluaXNoRGF0ZSI6IjIwMTgtMDktMTAgMTA6Mzc6MzUiLCJwYXlUb29sIjoiRVhQUiIsInRyYWRlQW1vdW50Ijp7ImFtb3VudCI6MSwiYmlnRGVjaW1hbFl1YW4iOjAuMDEsImN1cnJlbmN5Q29kZSI6IkNOWSIsInN0cmluZ1l1YW4iOiIwLjAxIn0sInRyYWRlU3RhdHVzIjoiRklOSSIsInRyYWRlVHlwZSI6IlRfR0VOIn1dLCJzdWJUcmFkZVR5cGUiOiJHRU5TIiwic3VibWl0dGVyIjp7ImN1c3RvbWVyTm8iOiIzNjAwODAwMDAyMjI5NDUzMTQifSwidHJhZGVBbW91bnQiOnsiYW1vdW50IjoxLCJiaWdEZWNpbWFsWXVhbiI6MC4wMSwiY3VycmVuY3lDb2RlIjoiQ05ZIiwic3RyaW5nWXVhbiI6IjAuMDEifSwidHJhZGVGaW5pc2hlZERhdGUiOiIyMDE4LTA5LTEwIDEwOjM4OjUyIiwidHJhZGVObyI6IjIwMTgwOTEwMTAzNDQ1MDExMDEwMDIwMTE5NDMyMDgwIiwidHJhZGVTdGF0dXMiOiJGSU5JIiwidHJhZGVTdWJqZWN0IjoieHlmIiwidHJhZGVUeXBlIjoiVF9TSEEifX0=\",\"encType\":\"AP7\",\"formatType\":\"JSON\",\"merId\":\"360080000222945314\",\"merType\":\"CCC\",\"reqId\":\"b18d9913-91ff-4cc6-9c20-d2a64b5e9448\",\"reqTime\":\"2018-09-10 10:38:52\",\"sign\":\"635FAEC0E2BAA8949B8C295CC803CCB49976F9ED53826CF3F94DFCB56AC8FAAC\",\"signType\":\"SHA-256\"}";
        //$content = "{\"sign\":\"4261D30C2A23F367F5F585135176588C9152D368FCED104895487E9CDDE93B68\",\"formatType\":\"JSON\",\"encType\":\"AP7\",\"signType\":\"SHA-256\",\"data\":\"eyJ0cmFkZVN0YXR1cyI6IkZJTkkiLCJkZXNjIjoi5oiQ5YqfIiwidHJhZGVObyI6IjIwMTkwNjEwMTg0MzU5MjAxMTI1MDI2OTQ4NTE5MSIsImN1c3RvbWVyTm8iOiIzNjAwODAwMDQwMDI3NDExNjYiLCJmaW5pc2hEYXRlIjoiMjAxOTA2MTAxODQ0MDAiLCJvdXRUcmFkZU5vIjoiMjAxOTIwMTkyMDE5MjAxOTA2MDYxMDEwMTgxODQzNDM1OTU5eVVZSlR1eDQiLCJjb2RlIjoiMDAwMCIsImNhcmRUeXBlIjoiREUiLCJiYW5rQ29kZSI6IkJDTSIsImN1cnJlbmN5IjoiQ05ZIiwicGF5VG9vbCI6IkVYUFIiLCJleHRlbmRQYXJhbXMiOiJ7XCJzZW5kU01TXCI6XCJmYWxzZVwifSIsInRyYWRlQW1vdW50IjoiMSIsIm1lcmNoYW50Tm8iOiIxMTAzNTg4NTAwMDMiLCJtYXNrQ2FyZE5vIjoiMzY1OSIsInRyYWRlU3ViamVjdCI6IuiuouWNleaRmOimgSJ9\",\"merType\":\"CCC\",\"merId\":\"360080004002741166\",\"charset\":\"UTF-8\",\"\/jdCallBack\":null}";
        return CreditPayment::verifySignAndTradeStatus($content);
    }
}
 