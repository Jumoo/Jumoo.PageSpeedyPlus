-- AveScore
SELECT sum(Score) / count(*) as AveScore from SpeedyResults_View where MonthId = 30 and Platform = 'mobile';

-- Ave Size
SELECT (sum(total) / count(*)) / 1024 as Size from SpeedyResults_View where MonthId = 30 and Platform = 'mobile';

-- Test Count 
select count(*) from Speedy_Result;

-- New Sites
select count(*) from newsites where NewMonthId = 30;

-- Year sites
select count(*) from newsites where NewMonthId > (30-12);

-- total new 
select count(*) from newsites
