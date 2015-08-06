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