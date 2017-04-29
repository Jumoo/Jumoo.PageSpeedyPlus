CREATE VIEW SpeedyResults_View AS 
	SELECT Sites.Id as SiteId, Sites.Name As Site, Sites.Url, Months.Id as MonthId, Months.Name as Month, Speedy_Result.* FROM SITES
		INNER JOIN Speedy on Speedy.SiteId = Sites.Id
		INNER JOIN Months on Months.Id = Speedy.MonthId
		INNER JOIN Speedy_Result on Speedy_Result.SpeedyID = Speedy.Id;

CREATE VIEW Checker_View AS
	select Sites.Id, Sites.name, Checker.MonthID, Checker.Status, Checker.Errors from Sites 
		INNER JOIN Checker ON Sites.ID = Checker.SiteId;
		
CREATE VIEW NewSites_View as 		
	select Sites.Id, Sites.Name, Sites.Url, NewSites.newMonthId, NewSites.newMonthId - 1 as lastMonthId from NewSites
		INNER JOIN Sites ON Sites.Id = newSites.SiteId;
		
		
CREATE VIEW AppScores as 
select Speedy.MonthId as MonthId, Platform, Category, Application, Count(*) as SiteCount, Sum(Score)/Count(*) as AveScore, Max(Score) as TopScore, Min(Score) as LowScore from Speedy 
	INNER JOIN Speedy_Result on Speedy_Result.SpeedyID = Speedy.Id
	INNER JOIN Features on Features.SiteId = Speedy.SiteId 
	WHERE Speedy.MonthId = 19 and category = 'cms' and Features.MonthId = 19 and Score > 0
	GROUP BY Platform, Category, Application;

	
select Speedy.MonthId as MonthId, Platform, Category, Application, Count(*) as SiteCount, Sum(Score)/Count(*) as AveScore, Max(Score) as TopScore, Min(Score) as LowScore from Speedy
	INNER JOIN Speedy_Result on Speedy_Result.SpeedyID = Speedy.Id 
	INNER JOIN Features on Features.SiteId = Speedy.SiteId 
	WHERE Speedy.MonthId = 19 and Features.MonthId = 19 and Category = 'cms' and Score > 0 and Platform = 'mobile' 
	GROUP BY Application;
	
select sum(score), count(*), sum(score)/count(*) as ave, Features.Application from SpeedyResults_View 
	INNER JOIN Features on Features.SiteId = SpeedyResults_View.SiteId 
	where SpeedyResults_View.MonthId = 27 and Features.MonthId = 27
	and Features.Category = 'cms' 
	and SpeedyResults_view.SiteId in 
		(Select SiteId from newSites where newMonthId > 20) 
	and platform = 'desktop' group by features.application having count(*) > 1;

CREATE TABLE CMS_Speeds ( )

INSERT INTO CMS_Speeds (MonthId, Max, Min, Cms) 
SELECT 31, max(Speedy_Result.Score), min(Speedy_Result.Score), Features.Application from Speedy 
	INNER JOIN Features ON Features.SiteId = Speedy.SiteId
	INNER JOIN Speedy_Result ON Speedy_Result.SpeedyId = Speedy.Id
	WHERE Features.category = 'cms' and Speedy.MonthId = 31 and Speedy_Result.Platform = 'desktop'
	GROUP BY Features.Application; 


select Speedy.MonthId as MonthId, Platform, Category, Application, Count(*) as SiteCount, Sum(Score)/Count(*) as AveScore, Max(Score) as TopScore, Min(Score) as LowScore from Speedy 
	INNER JOIN Speedy_Result on Speedy_Result.SpeedyID = Speedy.Id 
	INNER JOIN Features on Features.SiteId = Speedy.SiteId 
	WHERE Speedy.MonthId = :month and Features.MonthId = :month and Category = :cat and Score > 0 and Platform = "mobile"  
	GROUP BY Application ORDER BY SiteCount DESC; 


SELECT Max(Score) FROM Speedy
	INNER JOIN Speedy_Result on Speedy_Result.SpeedyID = Speedy.Id 
	INNER JOIN Features on Features.SiteId = Speedy.SiteId
	WHERE Speedy.MonthId = 31 and Features.MonthId = 31 and Features.Category = 'cms' and Score > 0 and Platform = "mobile";

SELECT Features.Application from Features 
	INNER JOIN Sites on Sites.Id = Features.SiteId
	where siteid = 199 and features.MonthId = 31 and Features.Category = 'cms');