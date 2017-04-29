-- AveScore
SELECT sum(Score) / count(*) as AveScore from SpeedyResults_View where MonthId = 35 and Platform = 'mobile';

-- Ave Size
SELECT (sum(total) / count(*)) / 1024 as Size from SpeedyResults_View where MonthId = 35 and Platform = 'mobile';

-- Test Count 
select count(*) from Speedy_Result;

-- New Sites
select count(*) from newsites where NewMonthId = 35;

-- Year sites
select count(*) from newsites where NewMonthId > (35-12);

-- total new 
select count(*) from newsites

-- features
select count(*) from features;

-- crawled, pages, docs
select count(*), sum(pages), sum(docs) from sitelinks where pages > 0 ;

-- Domains
select count(*) from domains;

-- Domain Apps
select count(*) from domain_features where application != 'error';

select ()